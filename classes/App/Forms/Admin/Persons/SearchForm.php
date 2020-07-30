<?php

/**
 * Formulaire de recherche de personnes du panneau d'administration
 */

namespace App\Forms\Admin\Persons;

use App\Forms\Search\SearchForm as Form;
use App\Admin\Person;

class SearchForm extends Form {
	
	public const DATA_SEARCH = 'admin-person-search';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'admin-person-search-form';
	
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
		self::DATA_SEARCH => 'Rechercher une personne',
	];
	
	/****************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Person::adminListUri());
	}
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		return 'Recherche d\'une personne';
	}
	
	/****************************************************************************/
	
}