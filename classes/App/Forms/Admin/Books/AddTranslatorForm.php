<?php

/**
 * Formulaire d'ajout d'un traducteur
 */

namespace App\Forms\Admin\Books;

class AddTranslatorForm extends AddContributorForm {
	
	public const DATA_PERSON = 'add-translator';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name	= 'admin-book-add-translator';
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [
		self::DATA_PERSON => 'Rechercher un traducteur',
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_PERSON => NULL,
	];
	
	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_PERSON => self::FIELD_AUTOCOMPLETE,
	];
	
	/***********************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		return 'Ajouter un traducteur';
	}
	
	/***********************************************************/
	
}