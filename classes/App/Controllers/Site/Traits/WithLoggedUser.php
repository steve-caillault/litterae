<?php

/**
 * Trait permettant de récupérer l'utilisateur connecté du site et de vérifier les permissions
 */

namespace App\Controllers\Site\Traits;

use App\Reader;

use App\Controllers\Traits\WithLoggedUser as WithLoggedUserBase;

trait WithLoggedUser {
	
	use WithLoggedUserBase;
	
	/**
	 * Permission requise
	 * @var string
	 */
	protected string $_required_permissions = Reader::PERMISSION_READER;
	
	/**
	 * Classe utilisateur à utiliser
	 * @var string
	 */
	protected static string $_user_class = Reader::class;
	
}