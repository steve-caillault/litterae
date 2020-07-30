<?php

/**
 * Trait utilisé pour les contrôleurs utilisant un livre
 */

namespace App\Controllers\Traits;

use App\{
	Site,
	BaseBook as Book,
	Book as BookSite,
	Admin\Book as BookAdmin
};

trait WithBook {
	
	/**
	 * Livre à gérer
	 * @var Book
	 */
	protected ?Book $_book = NULL;
	
	/**
	 * Affecte le livre de la requête HTTP
	 * @return void
	 */
	protected function _retrieveBook() : void
	{
		$siteType = Site::type();
		
		$class = ($siteType == Site::TYPE_ADMIN) ? BookAdmin::class : BookSite::class;

		$bookId = getArray($this->request()->parameters(), 'bookId');
		if($bookId !== NULL)
		{
			$this->_book = $class::factory($bookId);
			if($this->_book === NULL)
			{
				exception('Le livre n\'existe pas.', 404);
			}
		}
	}
	
}