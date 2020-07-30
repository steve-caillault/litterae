<?php

/**
 * Formulaire de recherche de pays du panneau d'administration
 */

namespace App\Forms\Admin\Countries;

use App\Forms\Search\SearchForm as Form;
use App\Admin\Country;

class SearchForm extends Form {
	
	public const DATA_SEARCH = 'admin-country-search';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name	= 'admin-country-search-form';
	
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
		self::DATA_SEARCH => 'Rechercher un pays',
	];
	
	/****************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Country::adminListUri());
	}
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		return 'Recherche d\'un pays';
	}
	
	/****************************************************************************/
	
}