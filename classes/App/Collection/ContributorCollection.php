<?php

/**
 * Gestion des contributeurs d'un livre
 */

namespace App\Collection;

use Root\DB;
use Root\Database\Query\Builder\Select as QueryBuilder;
/***/
use App\{ 
	Person, 
	PersonFollowed,
	BaseBook as Book,
	Contributor,
	Reader
};
use App\Traits\Collection\WithPerson;

class ContributorCollection extends Collection {
	
	use WithPerson;
	
	const ORDER_BY_NAME = 'name';
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Person::class;
	
	/**
	 * Type de contributeur filtré
	 * @var string
	 */
	private string $_contributor_type;
	
	/***************************************************************/
	
	/**
	 * Constructeur
	 * @param mixed $params
	 */
	public function __construct($params = NULL)
	{
		parent::__construct($params);
		$this->_query->distinct(TRUE);
		$this->_join(Contributor::$table, Contributor::$table . '.person', $this->_table . '.id');
	}
	
	/***************************************************************/
	
	/* FILTRES */
	
	/**
	 * Filtre le type de contributeur
	 * @param string $type
	 * @return self
	 */
	public function type(string $type) : self
	{
		$this->_contributor_type = $type;
		$this->_query->where(Contributor::$table . '.type', '=', $type);
		return $this;
	}
	
	/**
	 * Filtre le livre
	 * @param Book $book
	 * @return self
	 */
	public function book(Book $book) : self
	{
		$this->_query->where(Contributor::$table . '.book', '=', $book->id);
		return $this;
	}
	
	/**
	 * Filtre les contributeurs suivis par le lecteur en paramètre
	 * @param Reader $reader
	 * @return self
	 */
	public function followedBy(Reader $reader) : self
	{
		$PersonsFollowedTable = PersonFollowed::$table;
		$contributorsTable = Contributor::$table;
		
		$this->_joinMultipleRules($PersonsFollowedTable, [
			[
				'left' => $contributorsTable . '.person',
				'operator' => '=',
				'right' => DB::expression($PersonsFollowedTable . '.person'),
			],
			[
				'left' => $contributorsTable . '.type',
				'operator' => '=',
				'right' => DB::expression($PersonsFollowedTable . '.type'),
			],
		]);
		
		$this->_query->where($PersonsFollowedTable . '.reader', '=', $reader->id);
		
		return $this;
	}
	
	/***************************************************************/
	
	/**
	 * Retourne la requête de calcul du nombre de résultats
	 * @return QueryBuilder
	 */
	protected function _countResultsQuery() : QueryBuilder
	{
		$query = parent::_countResultsQuery();
		
		$query->distinct(FALSE);
		
		$field = strtr('COUNT(DISTINCT(:field)) AS nb', [
			':field' => Contributor::$table . '.person',
		]);
		
		$query->select([
			DB::expression($field),
		]);
		
		return $query;
	}
	
	/***************************************************************/
	
}