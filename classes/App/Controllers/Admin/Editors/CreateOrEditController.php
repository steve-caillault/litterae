<?php

/**
 * Création et édition d'un éditeur
 */

namespace App\Controllers\Admin\Editors;

use Root\Request;
/***/
use App\Controllers\Admin\HomeController as Controller;
use App\Admin\{ Editor };
use App\Admin\HTML\Menu\EditorMenuHTML;
use App\Forms\Admin\Editors\CreateOrEditForm as Form;
use App\HTML\Menu\MenuHTML;

class CreateOrEditController extends Controller {
	
	/**
	 * Editeur à gérer
	 * @var Editor
	 */
	protected ?Editor $_editor = NULL;
	
	/**
	 * Formulaire d'édition
	 * @var Form
	 */
	private Form $_edit_form;
	
	/********************************************************/
	
	/**
	 * - Récupération de l'éditeur
	 * - Fil d'Ariane
	 * @return void
	 */
	public function before() : void
	{
		// Récupération de l'éditeur
		$editorId = getArray($this->request()->parameters(), 'editorId');
		if($editorId !== NULL)
		{
			$this->_editor = Editor::factory($editorId);
			if($this->_editor === NULL)
			{
				exception('L\'éditeur n\'existe pas.', 404);
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
		$this->_menus[MenuHTML::TYPE_SECONDARY] = EditorMenuHTML::factory([
			'editor' => $this->_editor,
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
			'editor' => $this->_editor,
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
			'name' => 'Editeurs',
			'alt' => 'Consulter la liste des éditeurs.',
			'href' => Editor::adminListUri(),
		]);
		
		// Edition
		if($this->_editor !== NULL)
		{
			$this->_site_breadcrumb->add([
				'name' => $this->_editor->name,
				'alt' => strtr('Edition de l\'éditeur :name.', [ ':name' => $this->_editor->name, ]),
				'href' => $this->_editor->adminEditUri(),
			]);
		}
		// Création
		elseif($routeName == 'admin.editors.add')
		{
			$this->_site_breadcrumb->add([
				'name' => 'Création',
			]);
		}
	}
	
	/********************************************************/
	
}