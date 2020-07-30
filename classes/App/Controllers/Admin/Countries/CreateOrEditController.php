<?php

/**
 * Création et édition d'un pays
 */

namespace App\Controllers\Admin\Countries;

use Root\{ Request };
/***/
use App\Controllers\Admin\HomeController as Controller;
use App\Admin\{ Country };
use App\Admin\HTML\Menu\CountryMenuHTML;
use App\Forms\Admin\Countries\CreateOrEditForm as Form;
use App\HTML\Menu\MenuHTML;

class CreateOrEditController extends Controller {
	
	/**
	 * Pays à gérer
	 * @var Country
	 */
	protected ?Country $_country = NULL;
	
	/**
	 * Formulaire d'édition
	 * @var Form
	 */
	private Form $_edit_form;
	
	/********************************************************/
	
	/**
	 * - Récupération du pays
	 * - Fil d'Ariane
	 * @return void
	 */
	public function before() : void
	{
		// Récupération du pays
		$countryCode = getArray($this->request()->parameters(), 'countryCode');
		if($countryCode !== NULL)
		{
			$this->_country = Country::factory($countryCode);
			if($this->_country === NULL)
			{
				exception('Le pays n\'existe pas.', 404);
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
		$this->_menus[MenuHTML::TYPE_SECONDARY] = CountryMenuHTML::factory([
			'country' => $this->_country,
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
			'country' => $this->_country,
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
			'name' => 'Pays',
			'alt' => 'Consulter la liste des pays.',
			'href' => Country::adminListUri(),
		]);
		
		// Edition
		if($this->_country !== NULL)
		{
			$this->_site_breadcrumb->add([
				'name' => $this->_country->name,
				'alt' => strtr('Edition du pays :name.', [ ':name' => $this->_country->name, ]),
				'href' => $this->_country->adminEditUri(),
			]);
		}
		// Création
		elseif($routeName == 'admin.countries.add')
		{
			$this->_site_breadcrumb->add([
				'name' => 'Création',
			]);
		}
	}
	
	/********************************************************/
	
}