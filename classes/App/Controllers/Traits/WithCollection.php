<?php

/**
 * Trait utilisé pour les contrôleurs utilisant une collection d'un éditeur
 */

namespace App\Controllers\Traits;

use App\{ Collection };

trait WithCollection {
	
	/**
	 * Collection à gérer
	 * @var Collection
	 */
	protected ?Collection $_collection = NULL;
	
	/**
	 * Affecte la collection de la requête HTTP
	 * @return void
	 */
	protected function _retrieveCollection() : void
	{
		$collectionId = getArray($this->request()->parameters(), 'collectionId');
		if($collectionId !== NULL)
		{
			$this->_collection = Collection::factory($collectionId);
			if($this->_collection === NULL)
			{
				exception('La collection n\'existe pas.', 404);
			}
		}
	}
	
}