<?php

/**
 * Formulaire de connexion d'un administrateur
 */

namespace App\Forms\Admin\Users;

use App\Forms\LoginForm as ProcessForm;
use App\Admin\User;

class LoginForm extends ProcessForm
{
    /**
     * Nom du formulaire
     * @var string
     */
    public static string $name = 'admin-login';
    
	/**
	 * Permission requise pour être autorisé à se connecter
	 * @var array
	 */
	protected array $_required_permissions = [ User::PERMISSION_ADMIN, ];
	
	/**
	 * Classe des utilisateurs à utiliser
	 * @var string
	 */
	protected static string $_user_class = User::class;
	
	/**********************************************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		return 'Connexion au panneau d\'administration';
	}

	/**********************************************************************************************************/
	
}