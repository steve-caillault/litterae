<?php

/**
 * Page de connexion d'un administrateur
 */

namespace App\Controllers\Admin\Auth;

use App\Controllers\Admin\HomeController as Controller;
use App\Controllers\Auth\WithLogin;
use App\Admin\User;
use App\Forms\Admin\Users\LoginForm as Form;

class LoginController extends Controller {
	
	use WithLogin;
	
	/**
	 * Vrai si la page demande un utilisateur connecté
	 * @var bool
	 */
	protected bool $_required_user = FALSE;
	
	/**
	 * Classe du formulaire de connexion à utiliser
	 * @var string
	 */
	protected static string $_login_form_class = Form::class;
	
	/**
	 * Classe de l'utilisateur à utiliser
	 * @var string
	 */
	protected static string $_user_class = User::class;
	
	/**
	 * Nom de la route par défaut où rediriger l'utilisateur
	 * @var string
	 */
	protected static string $_default_route_name = 'admin';
	
	/**************************************************************/
	
}