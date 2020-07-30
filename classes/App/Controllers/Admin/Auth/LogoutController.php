<?php

/**
 * Page de déconnexion d'un administrateur
 */

namespace App\Controllers\Admin\Auth;

use App\Controllers\HTML\ContentController;
use App\Admin\User;
use App\Controllers\Auth\WithLogout;

class LogoutController extends ContentController {
	
	use WithLogout;
	
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