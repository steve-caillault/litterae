<?php

/**
 * Contrôleur de base pour les appels Ajax
 */

namespace App\Controllers;

use Root\{ Controller, Response };

abstract class AjaxController extends Controller {
	
	protected const STATUS_SUCCESS = 'SUCCESS';
	protected const STATUS_ERROR = 'ERROR';
	
	/**
	 * Données retournées par l'appel Ajax
	 * @var array
	 */
	protected array $_response_data = [
		'status' => self::STATUS_ERROR,
		'data' => NULL,
	];
	
	/**********************************************/
	
	/**
	 * Retourne la réponse du contrôleur
	 * @param Response $response Si renseignée, la valeur à effecter
	 * @return mixed
	 */
	final public function response($response = NULL)
	{
		$json = json_encode($this->_response_data);
		$this->_response = new Response($json);
		$this->_response->addHeader('Content-Type', 'application/json');
		return $this->_response;
	}
	
	/**********************************************/
}