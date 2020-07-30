<?php

/**
 * Formulaire de connexion d'un lecteur
 */

namespace App\Forms\Reader;

use App\Forms\LoginForm as ProcessForm;
use App\Reader;

class LoginForm extends ProcessForm
{
    /**
     * Nom du formulaire
     * @var string
     */
    public static string $name = 'reader-login';
    
	/**
	 * Permission requise pour être autorisé à se connecter
	 * @var array
	 */
	protected array $_required_permissions = [ Reader::PERMISSION_READER, ];
	
	/**
	 * Classe des utilisateurs à utiliser
	 * @var string
	 */
	protected static string $_user_class = Reader::class;
	
	/**********************************************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		return 'Connexion à votre bibliothèque';
	}
	
	/**********************************************************************************************************/
	
}