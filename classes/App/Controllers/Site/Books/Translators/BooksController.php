<?php

/**
 * Liste des livres d'un traducteur
 */

namespace App\Controllers\Site\Books\Translators;

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
		
		$this->_head_title = strtr('Livres traduits par :name', [
			':name' => $this->_translator->fullName(),
		]);
		
		if($search !== NULL)
		{
			$this->_head_title = strtr('Recherche des livres traduits par :name', [
				':name' => $this->_translator->fullName(),
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
			'name' => 'Traducteurs',
			'href' => Route::retrieve('translators')->uri(),
			'alt' => 'Consulter la liste des traducteurs.',
		])->add([
			'name' => $this->_translator->fullName(),
			'href' => Route::retrieve('translators.item')->uri([
				'translatorId' => $this->_translator->id,
			]),
			'alt' => strtr('Consulter la liste des livres traduit par :name.', [
				':name' => $this->_translator->fullName(),
			]),
		]);
	}
	
	/**********************************************************/
	
}