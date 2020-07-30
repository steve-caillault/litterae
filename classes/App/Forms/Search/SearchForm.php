<?php

/**
 * Gestion d'un formulaire de recherche
 */

namespace App\Forms\Search;

use Root\{ Validation };
/***/
use App\Forms\ProcessForm;

abstract class SearchForm extends ProcessForm {
	
	public const DATA_SEARCH = 'search';
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'search-form';
	
	/**
	 * Méthode de transmission des données
	 * @var string
	 */
	protected static string $_method = self::METHOD_GET;
	
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
	 * Vrai si on doit afficher le titre du formulaire
	 * @var bool
	 */
	protected static bool $_with_title = FALSE;
	
	/**
	 * Texte du bouton de soumission du formulaire
	 * @var string
	 */
	protected static string $_submit_label = 'Rechercher';
	
	/**
	 * Liste des textes des champs lorsqu'ils sont vides
	 * @var array
	 */
	protected static array $_placeholders = [
		self::DATA_SEARCH => 'Effectuer une recherche',
	];
	
	/**
	 * Vrai si le formulaire doit être affiché sur une ligne
	 * @var bool
	 */
	protected static bool $_render_inline = TRUE;
	
	/**
	 * Terme de la recherche
	 * @var string
	 */
	private ?string $_search = NULL;
	
	/****************************************************************************/
	
	/* VALIDATION */
	
	/**
	 * Retourne l'objet Validation initialisé avec les réglés de validation
	 * @return Validation
	 */
	protected function _initValidation() : Validation
	{
		$rules = [
			static::DATA_SEARCH => [
				array('required'),
				array('min_length', array('min' => 3)),
				array('max_length', array('max' => 50)),
			],
		];
		
		$validation = Validation::factory([
			'data' 	=> $this->_data,
			'rules'	=> $rules,
		]);
		
		return $validation;
	}
	
	/****************************************************************************/
	
	/* TRAITEMENT DU FORMULAIRE */
	
	/**
	 * Méthode à exécuter si le formulaire est valide
	 * @return bool
	 */
	protected function _onValid() : bool
	{
		$search = getArray($this->_data, static::DATA_SEARCH);
		$success = ($search != NULL);
		if($success)
		{
			$this->_search = $search;
		}
		return $success;
	}
	
	/**
	 * Retourne l'URL de redirection où rediriger en cas de succès
	 * @return string
	 */
	public function redirectUrl() : ?string
	{
		return NULL;
	}
	
	/**
	 * Retourne le terme de la recherche
	 * @return string
	 */
	public function search() : ?string
	{
		return $this->_search;
	}
	
	/****************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne les attributs du formulaire
	 * @return array
	 */
	protected function _attributes() : array
	{
		$attributes = parent::_attributes();
		$attributes['class'] = trim(getArray($attributes, 'class', NULL) . ' search-form');
		return $attributes;
	}
	
	/****************************************************************************/
	
	/**
	 * Retourne les attributs du champs dont le nom est en paramètre
	 * @param string $name
	 * @return array
	 */
	protected function _inputAttributes(string $name) : array
	{
		$attributes = parent::_inputAttributes($name);
		
		$submitId = static::$name . '-submit';
		
		if($name == static::DATA_SEARCH)
		{
			$attributes['aria-labelledby'] = $submitId;
		}
		elseif($name == static::DATA_SUBMIT)
		{
			$attributes['id'] = $submitId;
			$attributes['aria-label'] = getArray(static::$_placeholders, static::DATA_SEARCH);
		}
		
		return $attributes;
	}
	
}