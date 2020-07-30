<?php

/**
 * Création et édition d'un collection
 */

namespace App\Controllers\Admin\Collections;

use Root\Request;
/***/
use App\Controllers\Admin\HomeController as Controller;
use App\Admin\{ Collection };
use App\Admin\HTML\Menu\CollectionMenuHTML;
use App\Forms\Admin\Collections\CreateOrEditForm as Form;
use App\HTML\Menu\MenuHTML;

class CreateOrEditController extends Controller {
	
	/**
	 * Editeur à gérer
	 * @var Collection
	 */
	protected ?Collection $_collection = NULL;
	
	/**
	 * Vrai si on charge les fichiers JavaScript
	 * @var bool
	 */
	protected bool $_active_javascript = TRUE;
	
	/**
	 * Formulaire d'édition
	 * @var Form
	 */
	private Form $_edit_form;
	
	/********************************************************/
	
	/**
	 * - Récupération du livre
	 * - Fil d'Ariane
	 * @return void
	 */
	public function before() : void
	{
		// Récupération de la collection
		$collectionId = getArray($this->request()->parameters(), 'collectionId');
		if($collectionId !== NULL)
		{
			$this->_collection = Collection::factory($collectionId);
			if($this->_collection === NULL)
			{
				exception('La collection n\'existe pas.', 404);
			}
		}
		
		parent::before();
	}
	
	/**
	 * Menu de navigation des pays
	 * @return void
	 */
	public function after() : void
	{
		$this->_menus[MenuHTML::TYPE_SECONDARY] = CollectionMenuHTML::factory([
			'collection' => $this->_collection,
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
		$form = Form::factory([
			'data' => $data,
			'collection' => $this->_collection,
		]);
		
		if(count($data) > 0)
		{
			$form->process();
			if($form->success())
			{
				redirect($form->redirectUrl());	
			}
		}
		
		$this->_edit_form = $form;
		
		$this->_main_content = $form->render();
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
			'name' => 'Collections',
			'alt' => 'Consulter la liste des collections.',
			'href' => Collection::adminListUri(),
		]);
		
		// Edition
		if($this->_collection !== NULL)
		{
			$this->_site_breadcrumb->add([
				'name' => $this->_collection->name,
				'alt' => strtr('Edition de la collection :name.', [ ':name' => $this->_collection->name, ]),
				'href' => $this->_collection->adminEditUri(),
			]);
		}
		// Création
		elseif($routeName == 'admin.collections.add')
		{
			$this->_site_breadcrumb->add([
				'name' => 'Création',
			]);
		}
	}
	
	/********************************************************/
	
}