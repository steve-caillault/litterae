<?php

/**
 * Formulaire de recherche de livre avec filtre d'auteur
 */

namespace App\Forms\Search;

use Root\{ Route };
/***/
use App\{ Person };

class SearchBookWithFilterAuthor extends SearchBookForm {
	
	/**
	 * Auteur dont les livres sont filtrés
	 * @var Person
	 */
	private Person $_author;
	
	/****************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION  */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
		$author = getArray($params, 'author');
		if($author === NULL OR ! $author instanceof Person)
		{
			exception('Auteur incorrect.');
		}
		
		$this->_author = $author;
		parent::__construct($params);
	}
	
	/****************************************************************************/
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Route::retrieve('authors.item')->uri([
			'authorId' => $this->_author->id,
		]));
	}
	
	/****************************************************************************/
	
}