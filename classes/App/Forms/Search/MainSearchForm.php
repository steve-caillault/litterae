<?php

/**
 * Formulaire de recherche principal du site
 */

namespace App\Forms\Search;

abstract class MainSearchForm extends SearchForm {
	
	public const DATA_SEARCH = 'main-search';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'main-search-form';
	
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
	
	/****************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne les attributs du formulaire
	 * @return array
	 */
	protected function _attributes() : array
	{
		$attributes = parent::_attributes();
		$attributes['id'] = 'search-form';
		return $attributes;
	}
	
	/****************************************************************************/
	
}