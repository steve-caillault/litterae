<?php

/**
 * Classe du modèle utilisant une personne
 */

namespace App;

use App\Traits\Models\{
	WithPerson as WithPersonTrait
};

abstract class WithPerson extends Model {
	
	use WithPersonTrait;
	
	/* Types de contributeur */
	public const TYPE_AUTHOR = 'AUTHOR';
	public const TYPE_TRANSLATOR = 'TRANSLATOR';
	public const TYPE_ILLUSTRATOR = 'ILLUSTRATOR';
	
	/**
	 * Class de l'objet personne à utiliser
	 * @var string
	 */
	protected static string $_person_class = Person::class;
	
	/**************************************************************/
	
	/**
	 * Types de personnes autorisés
	 * @var array
	 */
	private static array $_allowed_types = [
		self::TYPE_AUTHOR, self::TYPE_TRANSLATOR, self::TYPE_ILLUSTRATOR,
	];
	
	/**
	 * Type de personne
	 * @var string
	 */
	public ?string $type = NULL;
	
	/**************************************************************/
	
	/**
	 * Retourne les types de contributeurs autorisés
	 * @return array
	 */
	public static function allowedTypes() : array
	{
		return self::$_allowed_types;
	}
	
	/**************************************************************/
	
}