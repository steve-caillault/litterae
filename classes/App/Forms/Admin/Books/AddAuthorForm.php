<?php

/**
 * Formulaire d'ajout d'un auteur
 */

namespace App\Forms\Admin\Books;

class AddAuthorForm extends AddContributorForm {

	public const DATA_PERSON = 'add-author';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'admin-book-add-author';
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [
		self::DATA_PERSON => 'Rechercher un auteur',
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
		return 'Ajouter un auteur';
	}
	
	/***********************************************************/
	
}