<?php

/**
 * Formulaire de connexion
 */

namespace App\Forms;

use Root\{ Validation, Request };

abstract class LoginForm extends ProcessForm
{
	public const DATA_ID = 'id';
	public const DATA_PASSWORD = 'password';
	
	/**********************************************************************************************************/
	
	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_ID => self::FIELD_TEXT,
		self::DATA_PASSWORD => self::FIELD_PASSWORD,
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_ID => NULL,
		self::DATA_PASSWORD => NULL,
	];
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [
		self::DATA_ID => 'Identifiant',
		self::DATA_PASSWORD => 'Mot de passe',
	];
	
	/**
	 * Vrai si on doit afficher le titre du formulaire
	 * @var bool
	 */
	protected static bool $_with_title = FALSE;
	
	/**
	 * Permission requise pour être autorisé à se connecter
	 * @var array
	 */
	protected array $_required_permissions = [];
	
	/**
	 * Classe des utilisateurs à utiliser
	 * @var string
	 */
	protected static string $_user_class;
	
	/**********************************************************************************************************/
	
	/* VALIDATION */
	
	/**
	 * Retourne l'objet Validation initialisé avec les réglés de validation
	 * @return Validation
	 */
	protected function _initValidation() : Validation
	{
		$rules = [
			self::DATA_ID => [
				array('required'),
				array('min_length', array('min' => 5)),
				array('max_length', array('max' => 50)),
				array('model_exists', array(
					'class' => static::$_user_class,
					'criterias' => [ 'id' => ':value:' ],
				)),
			],
			self::DATA_PASSWORD => [
				array('required'),
				array('min_length', array('min' => 5)),
				array('max_length', array('max' => 50)),
			],
		];
		
		$validation = Validation::factory([
			'data' 	=> $this->_data,
			'rules'	=> $rules,
		]);
		
		return $validation;
	}
	
	/**********************************************************************************************************/
	
	/* TRAITEMENT DU FORMULAIRE */
	
	/**
	 * Méthode à exécuter si le formulaire est valide
	 * @return bool
	 */
	protected function _onValid() : bool
	{
		$id = getArray($this->_data, self::DATA_ID);
		$password = getArray($this->_data, self::DATA_PASSWORD);
		
		$user = static::$_user_class::factory($id);
		
		// Vérification du mot de passe
		$passwordVerified = $user->checkPassword($password);
		if(! $passwordVerified)
		{
			$this->_validation->addError(self::DATA_PASSWORD, 'incorrect', 'Le mot de passe est incorrect.');
			$this->_errors = $this->_onErrors();
			return FALSE;
		}
		
		// Vérification des permissions
		foreach($this->_required_permissions as $permission)
		{
			if(! $user->hasPermission($permission))
			{
				$this->_validation->addError(self::DATA_ID, 'forbidden', 'Vous n\'êtes pas autorisé à vous identifier avec ce compte.');
				$this->_errors = $this->_onErrors();
				return FALSE;
			}
		}
		
		// On met en session l'utilisateur connecté
		$user->login();
		
		return TRUE;
	}
	
	/**
	 * Retourne l'URL de redirection où rediriger en cas de succès
	 * @return string
	 */
	public function redirectUrl() : ?string
	{
		if($this->success())
		{
			$next = getArray(Request::current()->query(), 'next', '');
			return urldecode($next);
		}
		return NULL;
	}
	
	/**********************************************************************************************************/
	
}