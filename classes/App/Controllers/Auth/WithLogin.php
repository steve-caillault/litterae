<?php

/**
 * Page de connexion
 */

namespace App\Controllers\Auth;

use Root\{ Route };
use App\Forms\LoginForm;

trait WithLogin {
	
	/**
	 * Formulaire de connexion
	 * @var LoginForm
	 */
    private LoginForm $_login_form;
	
	/**************************************************************/

	/**
	 * Si un utilisateur est déjà connecté, on le redirige vers la page d'accueil
	 */
	public function before() : void
	{
		if(static::$_user_class::current() !== NULL)
		{
			$redirectUri = Route::retrieve(static::$_default_route_name)->uri();
			redirect($redirectUri);
		}
		
		parent::before();
	}

	/**
	 * Formulaire de connexion
	 */
	public function index() : void
	{
		$data = $this->request()->inputs();
		unset($data['next']);
		
		
		$form = static::$_login_form_class::factory([
			'data' => $data,
		]);
		
		if(count($data) > 0)
		{
			$form->process();
			if($form->success())
			{
				redirect($form->redirectUrl());
			}
		}
		
		$this->_login_form = $form;
		
		$this->_main_content = $form->render();
	}
	
	/**************************************************************/
	
	/**
	 * Gestion du titre de la page
	 * @return void
	 */
	protected function _managePageTitle() : void
	{
		$this->_page_title = $this->_login_form->title();
	}
	
	/**
	 * Gestion du fil d'ariane
	 * @return void
	 */
	protected function _manageBreadcrumb() : void
	{
		parent::_manageBreadcrumb();
		
		$this->_site_breadcrumb->add([
			'href' => NULL,
			'name' => 'Connexion',
		]);
	}
	
	/**************************************************************/
	
}