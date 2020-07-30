<?php

/**
 * Trait utilisé pour les contrôleurs utilisant un traducteur
 */

namespace App\Controllers\Traits;

use App\{ Person };

trait WithTranslator {
	
	/**
	 * Traducteur à gérer
	 * @var Person
	 */
	protected ?Person $_translator = NULL;
	
	/**
	 * Affecte le traducteur de la requête HTTP
	 * @return void
	 */
	protected function _retrieveTranslator() : void
	{
		$translatorId = getArray($this->request()->parameters(), 'translatorId');
		if($translatorId !== NULL)
		{
			$this->_translator = Person::factory($translatorId);
			if($this->_translator === NULL)
			{
				exception('Le traducteur n\'existe pas.', 404);
			}
		}
	}
	
}