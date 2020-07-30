<?php

/**
 * Classes pour les objets du modèle utilisant une personne
 */

namespace App\Traits\Models;

use App\BasePerson as Person;

trait WithPerson {
	
	/**
	 * Identifiant de la personne en base de données
	 * @var int
	 */
	public ?int $person = NULL;
	
	/**
	 * Personne
	 * @var Person
	 */
	private ?Person $_person = NULL;
	
	/**************************************************************/
	
	/**
	 * Retourne la personne
	 * @param Person $person Si renseigné, la personne à affecter
	 * @return Person
	 */
	public function person(?Person $person = NULL) : Person
	{
		if($person !== NULL)
		{
			$this->_person = $person;
			$this->person = $person->id;
		}
		elseif($this->_person === NULL AND $this->person !== NULL)
		{
			$this->_person = static::$_person_class::factory($this->person);
		}
		return $this->_person;
	}
	
	/**************************************************************/
	
}