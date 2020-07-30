<?php

/**
 * Trait permettant de récupérer l'utilisateur connecté du panneau d'administration et de vérifier les permissions
 */

namespace App\Controllers\Admin\Traits;

use App\Admin\User;

use App\Controllers\Traits\WithLoggedUser as WithLoggedUserBase;

trait WithLoggedUser {
	
	use WithLoggedUserBase;
	
	/**
	 * Permission requise
	 * @var string
	 */
	protected string $_required_permissions = User::PERMISSION_ADMIN;
	
	/**
	 * Classe utilisateur à utiliser
	 * @var string
	 */
	protected static string $_user_class = User::class;
	
}