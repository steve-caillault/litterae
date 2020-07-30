<?php

/**
 * Liste des livres d'une collection
 */

namespace App\Controllers\Site\Books\Collections;

use Root\Route;
/***/
use App\Controllers\Site\HomeController as Controller;

class BooksController extends Controller {
	
	/**
	 * Gestion du titre de la page
	 * @return void
	 */
	protected function _managePageTitle() : void
	{
		$search = $this->_retrieveSearchForm()->search();
		
		$this->_head_title = strtr('Livres de la collection :name', [
			':name' => $this->_collection->name,
		]);
		
		if($search !== NULL)
		{
			$this->_head_title = strtr('Recherche des livres de la collection :name', [
				':name' => $this->_collection->name,
			]);
		}
		
		$this->_page_title = $this->_head_title;
	}
	
	/**
	 * Gestion du fil d'ariane
	 * @return void
	 */
	protected function _manageBreadcrumb() : void
	{
		parent::_manageBreadcrumb();
		
		$this->_site_breadcrumb->add([
			'name' => 'Collections',
			'href' => Route::retrieve('collections')->uri(),
			'alt' => 'Consulter la liste des collections.',
		])->add([
			'name' => $this->_collection->name,
			'href' => Route::retrieve('collections.item')->uri([
				'collectionId' => $this->_collection->id,
			]),
			'alt' => strtr('Consulter la liste des livres de la collection :name.', [
				':name' => $this->_collection->name,
			]),
		]);
	}
	
	/**********************************************************/
	
}