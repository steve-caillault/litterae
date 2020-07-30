<?php

/**
 * Création et édition d'une personne
 */

namespace App\Controllers\Admin\Persons;

use Root\Request;
/***/
use App\Controllers\Traits\WithPerson;
use App\Controllers\Admin\HomeController as Controller;
use App\Admin\{ Person };
use App\Admin\HTML\Menu\PersonMenuHTML;
use App\Forms\Admin\Persons\CreateOrEditForm as Form;
use App\HTML\Menu\MenuHTML;

class CreateOrEditController extends Controller {
	
	use WithPerson;
	
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
	 * - Récupération de l'éditeur
	 * - Fil d'Ariane
	 * @return void
	 */
	public function before() : void
	{
		parent::before();
		$this->_retrievePerson();
	}
	
	/**
	 * Menu de navigation des pays
	 * @return void
	 */
	public function after() : void
	{
		$this->_menus[MenuHTML::TYPE_SECONDARY] = PersonMenuHTML::factory([
			'person' => $this->_person,
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
			'person' => $this->_person,
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
			'name' => 'Personnes',
			'alt' => 'Consulter la liste des personnes.',
			'href' => Person::adminListUri(),
		]);
		
		// Edition
		if($this->_person !== NULL)
		{
			$this->_site_breadcrumb->add([
				'name' => $this->_person->fullName(),
				'alt' => strtr('Edition de :name.', [ ':name' => $this->_person->fullName(), ]),
				'href' => $this->_person->adminEditUri(),
			]);
		}
		// Création
		elseif($routeName == 'admin.persons.add')
		{
			$this->_site_breadcrumb->add([
				'name' => 'Création',
			]);
		}
	}
	
	/********************************************************/
	
}