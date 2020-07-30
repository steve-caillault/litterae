<?php

/**
 * Trait utilisé pour les contrôleurs utilisant un illustrateur
 */

namespace App\Controllers\Traits;

use App\{ Person };

trait WithIllustrator {
	
	/**
	 * Illustrateur à gérer
	 * @var Person
	 */
	protected ?Person $_illustrator = NULL;
	
	/**
	 * Affecte le traducteur de la requête HTTP
	 * @return void
	 */
	protected function _retrieveIllustrator() : void
	{
		$illustratorId = getArray($this->request()->parameters(), 'illustratorId');
		if($illustratorId !== NULL)
		{
			$this->_illustrator = Person::factory($illustratorId);
			if($this->_illustrator === NULL)
			{
				exception('L\'illustrateur n\'existe pas.', 404);
			}
		}
	}
	
}