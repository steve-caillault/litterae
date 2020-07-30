<?php

/**
 * Liste des livres d'un auteur
 */

namespace App\Controllers\Site\Books\Authors;

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
		
		$this->_head_title = strtr('Livres de l\'auteur :name', [
			':name' => $this->_author->fullName(),
		]);
		
		if($search !== NULL)
		{
			$this->_head_title = strtr('Recherche des livres de l\'auteur :name', [
				':name' => $this->_author->fullName(),
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
			'name' => 'Auteurs',
			'href' => Route::retrieve('authors')->uri(),
			'alt' => 'Consulter la liste des auteurs.',
		])->add([
			'name' => $this->_author->fullName(),
			'href' => Route::retrieve('authors.item')->uri([
				'authorId' => $this->_author->id,
			]),
			'alt' => strtr('Consulter la liste des livres de l\'auteur :name.', [
				':name' => $this->_author->fullName(),
			]),
		]);
	}
	
	/**********************************************************/
	
}