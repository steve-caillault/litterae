<?php

/**
 * Formulaire de recherche d'éditeurs du panneau d'administration
 */

namespace App\Forms\Admin\Editors;

use App\Forms\Search\SearchForm as Form;
use App\Admin\Editor;

class SearchForm extends Form {
	
	public const DATA_SEARCH = 'admin-editor-search';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'admin-editor-search-form';
	
	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_SEARCH => self::FIELD_TEXT,
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_SEARCH => NULL,
	];
	
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
		return getURL(Editor::adminListUri());
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