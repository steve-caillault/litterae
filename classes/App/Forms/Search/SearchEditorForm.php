<?php

/**
 * Formulaire de recherche d'éditeur
 */

namespace App\Forms\Search;

use Root\{ Route };

class SearchEditorForm extends MainSearchForm {
	
	/**
	 * Liste des textes des champs lorsqu'ils sont vides
	 * @var array
	 */
	protected static array $_placeholders = [
		self::DATA_SEARCH => 'Rechercher un éditeur',
	];
	
	/****************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Route::retrieve('editors')->uri());
	}
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		return 'Recherche d\'un éditeur';
	}
	
	/****************************************************************************/
	
}