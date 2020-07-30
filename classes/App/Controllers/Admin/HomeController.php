<?php

/**
 * Page d'accueil du panneau d'administration
 */

namespace App\Controllers\Admin;

use App\Controllers\HTML\ContentController;
use App\Controllers\Admin\Traits\WithLoggedUser;
use App\Admin\{ Country, Book, Editor, Collection, Person };
use App\HTML\Menu\MenuHTML;
use App\Site;
/***/
use Root\{ Route };

class HomeController extends ContentController {
	
	use WithLoggedUser;
	
	/**
	 * Vrai si la page demande un utilisateur connecté
	 * @var bool
	 */
	protected bool $_required_user = TRUE;
	
	/**********************************************************/
	
	/**
	 * Vérification si un administrateur est connecté
	 * @return void
	 */
	public function before() : void
	{
		$this->_retrieveUser();
		
		parent::before();
		
		Site::type(Site::TYPE_ADMIN);
	}
	
	/**
	 * 
	 */
	public function index() : void
	{
		$this->_main_content = MenuHTML::factory(MenuHTML::TYPE_MODULES)->addItem('countries.list', [
			'label' => 'Pays',
			'href' => Country::adminListUri(),
			'title' => 'Gestion des pays.',
		])->addItem('admin.persons.list', [
			'label' => 'Personnes',
			'href' => Person::adminListUri(),
			'title' => 'Gestion des personnes.',
		])->addItem('admin.books.list', [
			'label' => 'Livres',
			'href' => Book::adminListUri(),
			'title' => 'Gestion des livres.',
		])->addItem('admin.editors.list', [
			'label' => 'Editeurs',
			'href' => Editor::adminListUri(),
			'title' => 'Gestion des éditeurs.',
		])->addItem('admin.collections.list', [
			'label' => 'Collections',
			'href' => Collection::adminListUri(),
			'title' => 'Gestion des collections.',
		]);
	}
	
	/**********************************************************/
	
	/**
	 * Gestion du fil d'ariane
	 * @return void
	 */
	protected function _manageBreadcrumb() : void
	{
		parent::_manageBreadcrumb();
		
		$this->_site_breadcrumb->add([
			'name' => 'Panneau d\'administration',
			'href' => Route::retrieve('admin')->uri(),
			'alt' => 'Page d\'accueil du panneau d\'administration.',
		]);
	}
	
	/**
	 * Gestion du titre de la page
	 * @return void
	 */
	protected function _managePageTitle() : void
	{
		$this->_head_title = 'Panneau d\'administration';
	}
	
	/**********************************************************/
	
}