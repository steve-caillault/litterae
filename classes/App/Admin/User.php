<?php

/**
 * Gestion des utilisateurs
 */

namespace App\Admin;

use App\{ User as UserModel };

class User extends UserModel {
	
	/**
	 * Clé en session
	 */
	protected const SESSION_KEY = 'admin_user';
	
	/********************************************************/
	
	/**
	 * Retourne les noms des routes
	 * @return array
	 */
	protected static function _routeNames() : array
	{
		return [
			'login' => 'admin.login',
			'logout' => 'admin.logout',
		];
	}
	
	/********************************************************/
	
}