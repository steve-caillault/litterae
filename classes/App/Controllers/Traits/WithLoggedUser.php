<?php

/**
 * Trait permettant de récupérer l'utilisateur connecté et de vérifier les permissions
 */

namespace App\Controllers\Traits;

use App\{ User };
use Root\{ Request };

trait WithLoggedUser {
	
	/**
	 * Utilisateur connecté
	 * @var User
	 */
	protected ?User $_user = NULL;
	
	/**
	 * Récupére l'utilisateur
	 * @return void
	 */
	protected function _retrieveUser() : void
	{
		$class = static::$_user_class;

		$user = $class::current();
		
		if($this->_required_user)
		{
			if($user === NULL)
			{
				$request = Request::current();
				if($request->isAjax())
				{
					exception('Utilisateur requis.', 401);
				}
				else
				{
					redirect($class::loginUri());
				}
			}
			elseif(! $user->hasPermission($this->_required_permissions))
			{
				exception('Vous n\'êtes pas autorisé à accèder à cette page.', 403);
			}
		}
		
		$this->_user = $user;
	}
	
}