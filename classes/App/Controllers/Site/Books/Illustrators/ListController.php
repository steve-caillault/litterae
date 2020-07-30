<?php

/**
 * Page de la liste des traducteurs
 */

namespace App\Controllers\Site\Books\Illustrators;

use Root\Route;
/***/
use App\Controllers\Site\HomeController as Controller;
use App\Forms\Search\SearchIllustratorForm;
use App\Reader as User;
use App\HTML\Collection\IllustratorCollectionHTML;

class ListController extends Controller {
	
	/**
	 * Classe du formulaire de recherche à utiliser
	 * @var string
	 */
	protected static string $_search_form_class = SearchIllustratorForm::class;
	
	/**********************************************************/
	
	public function index() : void
	{
		$form = $this->_retrieveSearchForm();
		$search = $form->search();
	
		$books = IllustratorCollectionHTML::factory([
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
		
		$this->_head_title = ($search) ? 'Résultats de la recherche d\'illustrateurs' : 'Liste des illustrateurs';
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
			'alt' => 'Consulter la liste des illustrators.',
		]);
	}
	
	/**********************************************************/
	
}