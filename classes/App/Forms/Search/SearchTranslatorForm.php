<?php

/**
 * Formulaire de recherche d'un traducteur
 */

namespace App\Forms\Search;

use Root\{ Route };

class SearchTranslatorForm extends MainSearchForm {
	
	/**
	 * Liste des textes des champs lorsqu'ils sont vides
	 * @var array
	 */
	protected static array $_placeholders = [
		self::DATA_SEARCH => 'Rechercher un traducteur',
	];
	
	/****************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Route::retrieve('translators')->uri());
	}
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		return 'Recherche d\'un traducteur';
	}
	
	/****************************************************************************/
	
}