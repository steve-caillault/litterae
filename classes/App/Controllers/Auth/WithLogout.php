<?php

/**
 * Page de déconnexion
 */

namespace App\Controllers\Auth;

use Root\{ Route };

trait WithLogout {
	
	public function index() : void
	{
		static::$_user_class::logout();
		$redirectUri = Route::retrieve(static::$_default_route_name)->uri();
		redirect($redirectUri);
	}
	
	/**************************************************************/
	
}