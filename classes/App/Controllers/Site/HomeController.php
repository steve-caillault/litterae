<?php

/**
 * Page d'accueil
 */

namespace App\Controllers\Site;

use Root\{ View, Route };
/***/
use App\Controllers\Site\Traits\WithLoggedUser;
use App\Controllers\HTML\ContentController;
use App\Reader as User;
use App\Forms\Search\{ SearchForm, SearchBookForm };
use App\HTML\Collection\BookCollectionHTML;
use App\Controllers\Traits\{ WithAuthor, WithTranslator, WithIllustrator, WithEditor, WithCollection };

class HomeController extends ContentController {
	
	use WithLoggedUser, WithAuthor, WithTranslator, WithIllustrator, WithEditor, WithCollection;
	
	/**
	 * Vrai si la page demande un utilisateur connecté
	 * @var bool
	 */
	protected bool $_required_user = TRUE;
	
	/**
	 * Formulaire de recherche
	 * @var SearchForm
	 */
	protected ?SearchForm $_search_form = NULL;
	
	/**
	 * Classe du formulaire de recherche à utiliser
	 * @var string
	 */
	protected static string $_search_form_class = SearchBookForm::class;
	
	/**********************************************************/
	
	/**
	 * Vérification si un administrateur est connecté
	 * @return void
	 */
	public function before() : void
	{
		$this->_retrieveUser();
		$this->_retrieveAuthor();
		$this->_retrieveTranslator();
		$this->_retrieveIllustrator();
		$this->_retrieveEditor();
		$this->_retrieveCollection();
		parent::before();
	}
	
	public function after() : void
	{
		// Formulaire de recherche sur toutes les pages si l'utilisateur est connecté
		if(User::current() !== NULL)
		{
			$this->_main_content = $this->_searchFormRender() . $this->_main_content;
		}
		
		parent::after();
	}
	
	/**********************************************************/
	
	public function index() : void
	{
		$form = $this->_retrieveSearchForm();
		$search = $form->search();
		
		$bookListType = getArray($this->request()->inputs(), 'book-list-type');
		
		$books = BookCollectionHTML::factory([
			'reader' => User::current(),
			'search' => $search,
			'author' => $this->_author,
			'translator' => $this->_translator,
			'illustrator' => $this->_illustrator,
			'editor' => $this->_editor,
			'collection' => $this->_collection,
			'book_list_type' => ($bookListType) ? strtoupper($bookListType) : NULL,
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
		
		$this->_head_title = ($search !== NULL) ? 'Résultats de la recherche de livres' : 'Livres de la bibliothèque';
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
			'name' => 'Livres',
			'href' => Route::retrieve('home')->uri(),
			'alt' => 'Consulter la liste des livres.',
		]);
	}
	
	/**
	 * Retourne le formulaire de recherche
	 * @return SearchForm
	 */
	protected function _retrieveSearchForm() : SearchForm
	{
		if($this->_search_form === NULL)
		{
			$data = $this->request()->inputs();
		
			$this->_search_form = static::$_search_form_class::factory([
				'data' => $data,
				'author' => $this->_author,
				'translator' => $this->_translator,
				'illustrator' => $this->_illustrator,
				'editor' => $this->_editor,
				'collection' => $this->_collection,
			]);
			
			if(count($data) > 0)
			{
				$this->_search_form->process();
			}
		}
		return $this->_search_form;
	}
	
	/**
	 * Retourne le rendu du formulaire de recherche
	 * @return View
	 */
	protected function _searchFormRender() : View
	{
		$form = $this->_retrieveSearchForm()->render();
		
		return  View::factory('site/forms/search', [
			'form' => $form,
		]);
	}
	
	/**********************************************************/
	
}