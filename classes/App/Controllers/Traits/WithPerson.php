<?php

/**
 * Trait utilisé pour les contrôleurs utilisant une personne
 */

namespace App\Controllers\Traits;

use App\{
	Site,
	BasePerson,
	Person,
	Admin\Person as PersonAdmin
};

trait WithPerson {
	
	/**
	 * Personne à gérer
	 * @var BasePerson
	 */
    protected ?BasePerson $_person = NULL;
	
	/**
	 * Affecte la personne de la requête HTTP
	 * @return void
	 */
	protected function _retrievePerson() : void
	{
		$siteType = Site::type();
		
		$class = ($siteType == Site::TYPE_ADMIN) ? PersonAdmin::class : Person::class;
		
		// Récupération de la personne
		$personId = getArray($this->request()->parameters(), 'personId');
		if($personId !== NULL)
		{
			$this->_person = $class::factory($personId);
			if($this->_person === NULL)
			{
				exception('La personne n\'existe pas.', 404);
			}
		}
	}
	
}