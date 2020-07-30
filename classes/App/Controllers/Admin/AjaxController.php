<?php

/**
 * Gestion des requêtes Ajax du panneau d'administration
 */

namespace App\Controllers\Admin;

use Root\{ Validation };
use App\Controllers\Admin\Traits\WithLoggedUser;
/***/
use App\Site;
use App\Controllers\AjaxController as Controller;

class AjaxController extends Controller {
	
	use WithLoggedUser;
	
	/**
	 * Vrai si la page demande un utilisateur connecté
	 * @var bool
	 */
	protected bool $_required_user = TRUE;
	
	/*************************************************************/
	
	/**
	 * Vérification si un administrateur est connecté
	 * @return void
	 */
	public function before() : void
	{
		parent::before();
		Site::type(Site::TYPE_ADMIN);
		$this->_retrieveUser();
	}
	
	/**
	 * Recherche par autocomplètion
	 * @return void
	 */
	public function search() : void
	{
		$data = $this->request()->inputs();
		
		$validation = Validation::factory($data, [
			'model' => [
				array('required'),
				array('class_exists'),
			],
			'value' => [ 
				array('required'),
				array('min_length', array('min' => 3)),
				array('max_length', array('max' => 20)),
			],
		]);
		
		$models = [];
		
		$class = getArray($data, 'model');
		
		$validation->validate();
		if($validation->success())
		{
			$search = getArray($data, 'value');
			$collection = $class::adminSearch($search, 10);
			foreach($collection as $model)
			{
				$models[] = $model->adminSearchData();
			}
		}
		
		$this->_response_data = [
			'status' => self::STATUS_SUCCESS,
			'data' => $models,
		];
	}
	
	/*************************************************************/
	
}