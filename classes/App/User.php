<?php

/**
 * Gestion d'un utilisateur (lecteur ou administrateur)
 */

namespace App;

use Root\{ Route, Request };

abstract class User extends Model {
	
	const PERMISSION_ADMIN = 'ADMIN';
	const PERMISSION_READER = 'READER';
	
	/**
	 * Clé en session
	 */
	protected const SESSION_KEY = 'reader_user';
	
	/********************************************************/
	
	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'users';
	
	/**
	 * Vrai si la clé primaire est un auto-incrément
	 * @var bool
	 */
	protected static bool $_autoincrement = FALSE;
	
	/********************************************************/
	
	/**
	 * Identifiant de l'utilisateur
	 * @var string
	 */
	public string $id;
	
	/**
	 * Prénom
	 * @var string
	 */
	public string $first_name;
	
	/**
	 * Nom
	 * @var string
	 */
	public string $last_name;
	
	/**
	 * Mot de passe crypté
	 * @var string
	 */
	public string $password_hashed;
	
	/**
	 * Permissions de l'utilisteur (chaine JSON)
	 * @var string
	 */
	public string $permissions;
	
	/********************************************************/
	
	/**
	 * Utilisateur connecté
	 * @var self
	 */
	protected static ?self $_current = NULL;
	
	/**
	 * Vrai si l'utilisateur a été initialisé
	 * @var bool
	 */
    private static bool $_current_initialized = FALSE;
	
	/********************************************************/
	
	/**
	 * Retourne, affecte l'utilisateur connecté
	 * @param self $user Si renseigné, l'utilisateur à stocker en session
	 * @return self
	 */
	public static function current(?self $user = NULL) : ?self
	{
		if($user !== NULL)
		{
			self::$_current = $user;
			session()->change(static::SESSION_KEY, $user->id); // On ne stocke que l'identifiant de l'utilisateur en session
			self::$_current_initialized = TRUE;
		}
		elseif(self::$_current_initialized === FALSE)
		{
			$userId = session()->retrieve(static::SESSION_KEY);
			self::$_current_initialized = TRUE;
			if($userId !== NULL)
			{
				$class = static::class;
				self::$_current = $class::factory($userId);
			}
		}
		return self::$_current;
	}
	
	/**
	 * Connexion du membre
	 * @return void
	 */
	public function login() : void
	{
		static::current($this);
	}
	
	/**
	 * Déconnexion de l'utilisateur connecté
	 * @return void
	 */
	public static function logout() : void
	{
		session()->delete(static::SESSION_KEY);
		static::$_current = NULL;
	}
	
	/********************************************************/
	
	/**
	 * Retourne la valeur crypté du mot de passe en paramètre
	 * @param string $password
	 * @return string
	 */
	public static function passwordCrypted(string $password) : string
	{
		return password_hash($password, getConfig('password.algorithm'));
	}
	
	/**
	 * Vérifit que le mot de passe en paramètre correspond au mot de passe de l'utilisateur
	 * @param string $password
	 * @return bool
	 */
	public function checkPassword(string $password) : string
	{
		return password_verify($password, $this->password_hashed);
	}
	
	/**
	 * Retourne les permissions de l'utilisateur
	 * @return array
	 */
	private function _permissions() : array
	{
		return json_decode($this->permissions, TRUE);
	}
	
	/**
	 * Retourne si l'utisateur posséde la permission en paramètre
	 * @param string $permission
	 * @return bool
	 */
	public function hasPermission(string $permission) : bool
	{
		$permissions = $this->_permissions();
		$isAdmin = in_array(self::PERMISSION_ADMIN, $permissions);
		$hasPermssions = ($isAdmin OR in_array($permission, $permissions));
		return $hasPermssions;
	}
	
	/********************************************************/
	
	/**
	 * Retourne le nom complet de l'utilisateur
	 * @return string
	 */
	public function fullName() : string
	{
		return trim($this->first_name . ' ' . $this->last_name);
	}
	
	/********************************************************/
	
	/**
	 * Retourne les noms des routes
	 * @return array
	 */
	abstract protected static function _routeNames() : array;
	
	/**
	 * Retourne l'URI de connexion
	 * @return string
	 */
	public static function loginUri() : string
	{
		$nextUri = Request::detectUri();
		
		$routeName = getArray(static::_routeNames(), 'login');
		$uri = Route::retrieve($routeName)->uri();
		
		if($nextUri != NULL)
		{
			$uri .= '?' . http_build_query([
				'next' => $nextUri,
			]);
		}
		
		return $uri;
	}
	
	/**
	 * Retourne l'URI de déconnexion
	 * @return string
	 */
	public function logoutUri() : string
	{
		$routeName = getArray(static::_routeNames(), 'logout');
		return Route::retrieve($routeName)->uri();
	}
	
	/********************************************************/
	
}