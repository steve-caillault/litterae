<?php

/**
 * Gestion d'une personne
 */

namespace App;

abstract class BasePerson extends Model {

	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'persons';
	
	/*********************************************************************/
	
	/* CHAMPS EN BASE DE DONNEES */
	
	/**
	 * Identifiant de la personne en base de données
	 * @var int
	 */
	public ?int $id = NULL;
	
	/**
	 * Prénom de la personne
	 * @var string
	 */
	public ?string $first_name = NULL;
	
	/**
	 * Deuxième nom
	 * @var string
	 */
	public ?string $second_name = NULL;
	
	/**
	 * Nom de la personne
	 * @var string
	 */
	public ?string $last_name = NULL;
	
	/**
	 * Date de naissance
	 * @var string
	 */
	public ?string $birthdate = NULL;
	
	/**
	 * Code à deux lettre du pays de naissance
	 * @var string 
	 */
	public ?string $birth_country = NULL;
	
	/*********************************************************************/
	
	/**
	 * Pays de naissance
	 * @var Country
	 */
	private ?Country $_birth_country = NULL;
	
	/*********************************************************************/
	
	/**
	 * Retourne le pays de naissance
	 * @return Country
	 */
	public function birthCountry() : Country
	{
		if($this->_birth_country === NULL)
		{
			$this->_birth_country = Country::factory($this->birth_country);
		}
		return $this->_birth_country;
	}
	
	/**
	 * Retourne le nom complet de la personne
	 * @return string
	 */
	public function fullName() : string
	{
		$names = [
			$this->first_name, $this->second_name, $this->last_name,
		];
		
		$fullName = '';
		foreach($names as $name)
		{
			if($currentName = trim($name))
			{
				$fullName .= ' ' . $currentName;
			}
		}
		
		return trim($fullName);
	}
	
	/*********************************************************************/
	
}