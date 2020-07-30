<?php

/**
 * Gestion d'une liste de livres
 */

namespace App\Collection;

use Root\DB;
use Root\Database\Query\Builder\Select as QueryBuilder;
/***/
use App\{ Book, BookList, Reader, Contributor, Person, Editor, Collection as EditorCollection };

class BookCollection extends Collection {
	
	const ORDER_BY_TITLE = 'title';
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Book::class;

	/************************************************************/
	
	/* FILTRES */
	
	/**
	 * Filtre le contributeur en paramètre
	 * @param Person $person Personne représentant l'auteur
	 * @param string $type Type de contributeur
	 * @return self
	 */
	public function contributor(Person $person, string $type) : self
	{
		$contributorsTable = Contributor::$table;
		
		$this->_joinMultipleRules($contributorsTable, [
			[
				'left' => $contributorsTable . '.book',
				'operator' => '=',
				'right' => $this->_table . '.id',
			],
			[
				'left' => $contributorsTable . '.type',
				'operator' => '=',
				'right' => $type,
			],
		]);
		
		$this->_query->where($contributorsTable . '.person', '=', $person->id);
		
		return $this;
	}
	
	/**
	 * Filtre l'éditeur en paramètre
	 * @param Editor $editor Editeur à filtrer
	 * @return self
	 */
	public function editor(Editor $editor) : self
	{
		$collectionsTable = EditorCollection::$table;
		$booksTable = $this->_table;
		
		$this->_join($collectionsTable, $collectionsTable . '.id', $booksTable . '.collection', QueryBuilder::JOIN_LEFT);
		
		$this->_query->whereExpression(function($builder) use($booksTable, $collectionsTable, $editor) {
			
			$builder->where($booksTable . '.editor', '=', $editor->id)
					->orWhere($collectionsTable . '.editor', '=', $editor->id);
			
		});
		
		return $this;
	}
	
	/**
	 * Filtre la collection en paramètre
	 * @param EditorCollection $collection Collection à filtrer
	 * @return self
	 */
	public function collection(EditorCollection $collection) : self
	{
		$this->_query->where($this->_table . '.collection', '=', $collection->id);
		return $this;
	}
	
	/**
	 * Filtre la liste de la collection de livres dont le type est en paramètre
	 * @param Reader $reader Le lecteur dont on souhaite récupérer la liste des livres
	 * @param string $type Type de la liste de livres
	 * @return self
	 */
	public function bookList(Reader $reader, string $type) : self
	{
		$booksTable = $this->_table;
		$listTable = BookList::$table;
		
		$this->_join($listTable, $listTable . '.book', $booksTable . '.id');
		
		$this->_query	->where($listTable . '.reader', '=', $reader->id)
						->where($listTable . '.type', '=', $type);
		
		return $this;
	}
	
	/**
	 * Recherche par titre
	 * @param string $search
	 * @return self
	 */
	public function search(string $search) : self
	{
		$searchExpression = NULL;
		$words = explode(' ', $search);
		$searchExpression = trim(implode(' ', array_map(function($word) {
			if(strlen($word) < 3) // Car la valeur de innodb_ft_min_token_size est à 3 par défaut
			{
				return NULL;
			}
			return ('+' . $word . '*');
		}, $words)));
		
		
		$field = $this->_table . '.title';
		
		
		
		// On effectut une recherche FULL_TEXT
		$fieldMatch = DB::matchAgainst([ $field ], $searchExpression, 'IN BOOLEAN MODE');
		
		$this->_query->where($fieldMatch, '>', 0);
		
		// Tri de la recherche
		$expression = DB::expression(strtr(':field AS sort_search', [
			':field' => $fieldMatch,
		]));
		$this->_query->addSelect([
			$expression,
		])->orderBy(DB::expression($fieldMatch), self::DIRECTION_DESC);
		
		return $this;
	}
	
	/************************************************************/
	
	/* TRIS */
	
	/**
	 * Tri par nom
	 * @param string $direction
	 * @return self
	 */
	protected function _orderByTitle(string $direction = self::DIRECTION_ASC) : self
	{
		$this->_query->orderBy($this->_table . '.title', $direction);
		return $this;
	}
	
	/************************************************************/
	
}