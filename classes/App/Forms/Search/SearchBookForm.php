<?php

/**
 * Formulaire de recherche de livre
 */

namespace App\Forms\Search;

use Root\{ Route };

class SearchBookForm extends MainSearchForm {

	/**
	 * Liste des textes des champs lorsqu'ils sont vides
	 * @var array
	 */
	protected static array $_placeholders = [
		self::DATA_SEARCH => 'Rechercher un livre',
	];
	
	/****************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION  */
	
	/**
	 * Instanciation
	 * @return self
	 */
	public static function factory($params = NULL) : self
	{
		$author = getArray($params, 'author');
		if($author !== NULL)
		{
			return new SearchBookWithFilterAuthor($params);
		}
		
		$translator = getArray($params, 'translator');
		if($translator !== NULL)
		{
			return new SearchBookWithFilterTranslator($params);
		}
		
		$illustrator = getArray($params, 'illustrator');
		if($illustrator !== NULL)
		{
			return new SearchBookWithFilterIllustrator($params);
		}
		
		$editor = getArray($params, 'editor');
		if($editor !== NULL)
		{
			return new SearchBookWithFilterEditor($params);
		}
		
		$collection = getArray($params, 'collection');
		if($collection !== NULL)
		{
			return new SearchBookWithFilterCollection($params);
		}
		
		return new static($params);
	}
	
	/****************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Route::retrieve('home')->uri());
	}
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		return 'Recherche d\'un livre';
	}
	
	/****************************************************************************/
	
}