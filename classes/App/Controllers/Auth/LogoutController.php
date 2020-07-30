<?php

/**
 * Page de déconnexion d'un lecteur
 */

namespace App\Controllers\Auth;

use App\Controllers\HTML\ContentController;
use App\Reader;

class LogoutController extends ContentController {
	
	use WithLogout;
	
	/**
	 * Classe de l'utilisateur à utiliser
	 * @var string
	 */
	protected static string $_user_class = Reader::class;
	
	/**
	 * Nom de la route par défaut où rediriger l'utilisateur
	 * @var string
	 */
	protected static string $_default_route_name = 'home';
	
	/**************************************************************/
	
}