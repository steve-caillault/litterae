<?php

/**
 * Trait utilisé pour les contrôleurs utilisant un auteur
 */

namespace App\Controllers\Traits;

use App\{ Person };

trait WithAuthor {
	
	/**
	 * Auteur à gérer
	 * @var Person
	 */
    protected ?Person $_author = NULL;
	
	/**
	 * Affecte l'auteur de la requête HTTP
	 * @return void
	 */
	protected function _retrieveAuthor() : void
	{
		$authorId = getArray($this->request()->parameters(), 'authorId');
		if($authorId !== NULL)
		{
			$this->_author = Person::factory($authorId);
			if($this->_author === NULL)
			{
				exception('L\'auteur n\'existe pas.', 404);
			}
		}
	}
	
}