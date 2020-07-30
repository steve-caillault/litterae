<?php

/**
 * Gestion des abonnements du lecteur connecté
 */

namespace App\Controllers\Site\Persons;

use Root\{ Request };
/***/
use App\Controllers\Traits\WithPerson;
use App\Controllers\Site\Traits\WithLoggedUser;
use App\Controllers\AjaxController as Controller;
/***/
use App\Contributor;

class AjaxController extends Controller {
	
	use WithPerson, WithLoggedUser;
	
	/**
	 * Vrai si la page demande un utilisateur connecté
	 * @var bool
	 */
	protected bool $_required_user = TRUE;
	
	/**
	 * Type d'abonnement
	 * @var string
	 */
	private string $_contributor_type;
	
	/**************************************************************/
	
	/**
	 * 
	 */
	public function before() : void
	{
		parent::before();
		
		$this->_retrieveUser();
		$this->_retrievePerson();
		
		// Récupération du type d'abonnement à gérer
		$contributorType = strtoupper(Request::current()->parameter('contributorType'));
		if(! in_array($contributorType, Contributor::allowedTypes()))
		{
			exception('Type d\'abonnement incorrect.', 404);
		}
		
		$this->_contributor_type = $contributorType;
	}
	
	/**
	 * Ajoute le livre à la liste de lecture
	 * @return void
	 */
	public function follow() : void
	{
		$success = $this->_user->follow($this->_person, $this->_contributor_type);
		$this->_response_data = [
			'status' => self::STATUS_SUCCESS,
			'data' => $success,
		];
	}
	
	/**
	 * Désabonne le lecteur la personne
	 * @return void
	 */
	public function unfollow() : void
	{
		$success = $this->_user->unfollow($this->_person, $this->_contributor_type);
		$this->_response_data = [
			'status' => self::STATUS_SUCCESS,
			'data' => $success,
		];
	}
	
	/**************************************************************/
}
	