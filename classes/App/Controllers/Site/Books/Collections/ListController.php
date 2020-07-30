<?php

/**
 * Page de la liste des collections
 */

namespace App\Controllers\Site\Books\Collections;

use Root\Route;
/***/
use App\Controllers\Site\HomeController as Controller;
use App\Forms\Search\SearchCollectionForm;
use App\Reader as User;
use App\HTML\Collection\CollectionEditorCollectionHTML as CollectionHTML;

class ListController extends Controller {
	
	/**
	 * Classe du formulaire de recherche à utiliser
	 * @var string
	 */
	protected static string $_search_form_class = SearchCollectionForm::class;
	
	/**********************************************************/
	
	public function index() : void
	{
		$form = $this->_retrieveSearchForm();
		$search = $form->search();
		
		$books = CollectionHTML::factory([
			'reader' => User::current(),
			'search' => $search,
		])->render();
		
		$this->_main_content = $books->render();
	}
	
	/**********************************************************/
	
	/**
	 * Gestion du titre de la page
	 * @return void
	 */
	protected function _managePageTitle() : void
	{
		$search = $this->_retrieveSearchForm()->search();
		
		$this->_head_title = ($search) ? 'Résultats de la recherche de collection' : 'Liste des collections';
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
		]);
	}
	
	/**********************************************************/
	
}