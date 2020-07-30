<?php

/**
 * Liste des livres d'un éditeur
 */

namespace App\Controllers\Site\Books\Editors;

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
		
		$this->_head_title = strtr('Livres de l\'éditeur :name', [
			':name' => $this->_editor->name,
		]);
		
		if($search !== NULL)
		{
			$this->_head_title = strtr('Recherche des livres de l\'éditeur :name', [
				':name' => $this->_editor->name,
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
			'name' => 'Editeurs',
			'href' => Route::retrieve('editors')->uri(),
			'alt' => 'Consulter la liste des éditeurs.',
		])->add([
			'name' => $this->_editor->name,
			'href' => Route::retrieve('editors.item')->uri([
				'editorId' => $this->_editor->id,
			]),
			'alt' => strtr('Consulter la liste des livres de l\'éditeur :name.', [
				':name' => $this->_editor->name,
			]),
		]);
	}
	
	/**********************************************************/
	
}