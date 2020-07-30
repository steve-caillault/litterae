<?php

/**
 * Page de la liste des auteurs
 */

namespace App\Controllers\Site\Books\Authors;

use Root\Route;
/***/
use App\Controllers\Site\HomeController as Controller;
use App\Forms\Search\SearchAuthorForm;
use App\Reader as User;
use App\HTML\Collection\AuthorCollectionHTML;

class ListController extends Controller {
	
	/**
	 * Classe du formulaire de recherche à utiliser
	 * @var string
	 */
	protected static string $_search_form_class = SearchAuthorForm::class;
	
	/**********************************************************/
	
	public function index() : void
	{
		$form = $this->_retrieveSearchForm();
		$search = $form->search();
	
		$books = AuthorCollectionHTML::factory([
			'reader' => User::current(),
			'search' => $search,
			'followed' => (getArray($this->request()->query(), 'authors-followed') == 1),
		])->render();
		
		$this->_main_content = $books->render();
		
		$this->_active_javascript = TRUE;
	}
	
	/**********************************************************/
	
	/**
	 * Gestion du titre de la page
	 * @return void
	 */
	protected function _managePageTitle() : void
	{
		$search = $this->_retrieveSearchForm()->search();
		
		$this->_head_title = ($search) ? 'Résultats de la recherche d\'auteurs' : 'Liste des auteurs';
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
		]);
	}
	
	/**********************************************************/
	
}