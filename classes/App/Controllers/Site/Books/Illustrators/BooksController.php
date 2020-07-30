<?php

/**
 * Liste des livres d'un illustrateur
 */

namespace App\Controllers\Site\Books\Illustrators;

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
		
		$this->_head_title = strtr('Livres illustrés par :name', [
			':name' => $this->_illustrator->fullName(),
		]);
		
		if($search !== NULL)
		{
			$this->_head_title = strtr('Recherche des livres illustrés par :name', [
				':name' => $this->_illustrator->fullName(),
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
			'name' => 'Illustrateurs',
			'href' => Route::retrieve('illustrators')->uri(),
			'alt' => 'Consulter la liste des illustrateurs.',
		])->add([
			'name' => $this->_illustrator->fullName(),
			'href' => Route::retrieve('illustrators.item')->uri([
				'illustratorId' => $this->_illustrator->id,
			]),
			'alt' => strtr('Consulter la liste des livres illustrés par :name.', [
				':name' => $this->_illustrator->fullName(),
			]),
		]);
	}
	
	/**********************************************************/
	
}