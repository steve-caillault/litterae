<?php

/**
 * Page de connexion d'un lecteur
 */

namespace App\Controllers\Auth;

use App\Controllers\HTML\ContentController as Controller;
use App\Reader as User;
use App\Forms\Reader\LoginForm as Form;

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
	protected static string $_default_route_name = 'home';
	
	/**************************************************************/
	
}