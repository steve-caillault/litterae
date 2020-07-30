<?php

/**
 * Tâche permettant de créer un utilisateur pour le panneau d'administration
 * php cli admin:create-user firstName LastName password role
 */

namespace App\Tasks\Admin;

use Root\{ Task, Validation, URL };
/***/
use App\Admin\User;

class CreateUserTask extends Task {
	
	/**
	 * Identifiant de la tâche
	 * @var string
	 */
	protected string $_identifier = 'create-user';
	
	/*******************************************************/
	
	/**
	 * Exécute la tâche
	 * @return void
	 */
	protected function _execute() : void
	{
		$allowedPermissions = explode(',', strtolower(implode(',', [ 
			User::PERMISSION_ADMIN, 
			User::PERMISSION_READER,
		])));
		
		$parameters = $this->parameters();
		$rules = [
			[ // Prénom
				array('required'),
				array('max_length', [ 'max' => 100 ]),
			],
			[ // Nom
				array('required'),
				array('max_length', [ 'max' => 100 ]),
			],
			[ // Mot de passe
				array('required'),
			],
			[ // Permissions
				array('required'),
				array('in_array', [ 'array' => $allowedPermissions, ]),
			],
		];
		
		$validation = Validation::factory([
			'data' => $parameters, 
			'rules' => $rules,
		]);
		
		$validation->validate();
		
		if(! $validation->success())
		{
			exception('Paramètres incorrects.');
		}
		
		list($firstName, $lastName, $password, $role) = $parameters;
		
		$user = User::factory([
			'id' => URL::title(trim($firstName . ' ' . $lastName), '-'),
			'first_name' => $firstName,
			'last_name' => $lastName,
			'password_hashed' => User::passwordCrypted($password),
			'permissions' => json_encode([ strtoupper($role), ]),
		]);
		
		$success = $user->save();
		
		$this->_response = ($success) ? 'L\'utilisateur a été créée.' : 'L\'utilisateur n\'a pas été créée.';
	}
	
	/*******************************************************/

	
}