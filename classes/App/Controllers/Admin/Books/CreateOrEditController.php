<?php

/**
 * Création et édition d'un livre
 */

namespace App\Controllers\Admin\Books;

use Root\{ View, Request };
/***/
use App\Controllers\Admin\HomeController as Controller;
use App\Controllers\Traits\WithBook;
use App\Admin\{ Book, Contributor };
use App\Admin\HTML\Menu\BookMenuHTML;
use App\Admin\HTML\Collection\ContributorCollectionHTML;
use App\Forms\Admin\Books\{
	CreateOrEditForm as EditForm,
	AddContributorForm
};
use App\HTML\Menu\MenuHTML;

class CreateOrEditController extends Controller {
	
	use WithBook;
	
	/**
	 * Vrai si on charge les fichiers JavaScript
	 * @var bool
	 */
	protected bool $_active_javascript = TRUE;
	
	/**
	 * Formulaire d'édition
	 * @var EditForm
	 */
	private EditForm $_edit_form;
	
	/********************************************************/
	
	/**
	 * - Récupération du livre
	 * @return void
	 */
	public function before() : void
	{
		parent::before();
		$this->_retrieveBook();
	}
	
	/**
	 * Menu de navigation des pays
	 * @return void
	 */
	public function after() : void
	{
		$this->_menus[MenuHTML::TYPE_SECONDARY] = BookMenuHTML::factory([
			'book' => $this->_book,
		])->get();
		
		parent::after();
	}
	
	/********************************************************/
	
	/**
	 * Formulaire de création et d'édition
	 * @return void
	 */
	public function index() : void
	{
		$data = $this->request()->inputs();
		$editForm = EditForm::factory([
			'data' => $data,
			'book' => $this->_book,
		]);
		
		if(count($data) > 0)
		{
			$editForm->process();
			if($editForm->success())
			{
				redirect($editForm->redirectUrl());	
			}
		}
		
		$contributorsContent = NULL;
		
		// Pour une édition, gestion des auteurs
		if($this->_book !== NULL)
		{
			$contributorTypes = Contributor::allowedTypes();
			
			
			foreach($contributorTypes as $contributorType)
			{
				// Formulaire d'ajout d'un contributeur
				$addContributorForm = AddContributorForm::factory([
					'book' => $this->_book,
					'data' => $data,
					'contributor_type' => $contributorType,
				]);
				if(count($data) > 0)
				{
					$addContributorForm->process();
				}
				
				// Liste des contributeurs
				$contributors = ContributorCollectionHTML::factory([
					'type' => $contributorType,
					'book' => $this->_book,
				]);
			
				$contributorsContent .= View::factory('admin/books/contributors', [
					'form' => $addContributorForm->render(),
					'list' => $contributors->render(),
					'title' => strtr('Gestion des :type', [
						':type' => translate(strtolower($contributorType), [ 
							'count' => 2,
						]),
					]),
				]);
			}
		}
		
		$this->_edit_form = $editForm;
		
		$this->_main_content = implode('', [
			$editForm->render(),
			$contributorsContent,
		]);
	}
	
	/********************************************************/
	
	/**
	 * Gestion du titre de la page
	 * @return void
	 */
	protected function _managePageTitle() : void
	{
		$this->_page_title = $this->_edit_form->title();
		$this->_head_title = $this->_page_title;
	}
	
	/**
	 * Gestion du fil d'ariane
	 * @return void
	 */
	protected function _manageBreadcrumb() : void
	{
		parent::_manageBreadcrumb();
		
		$routeName = Request::current()->route()->name();
		
		$this->_site_breadcrumb->add([
			'name' => 'Livres',
			'alt' => 'Consulter la liste des livres.',
			'href' => Book::adminListUri(),
		]);
		
		// Edition
		if($this->_book !== NULL)
		{
			$this->_site_breadcrumb->add([
				'name' => $this->_book->title,
				'alt' => strtr('Edition du livre :title.', [ ':title' => $this->_book->title, ]),
				'href' => $this->_book->adminEditUri(),
			]);
		}
		// Création
		elseif($routeName == 'admin.books.add')
		{
			$this->_site_breadcrumb->add([
				'name' => 'Création',
			]);
		}
	}
		
	/********************************************************/
	
}