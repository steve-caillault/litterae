<?php

/**
 * Gestion des requêtes Ajax de gestion des contributeur d'un livre
 */

namespace App\Controllers\Admin\Books\Contributors;

use App\Controllers\Traits\WithContributor;
use App\Controllers\Admin\AjaxController as Controller;
use App\Forms\Admin\Books\AddContributorForm;

class AjaxController extends Controller {
	
	use WithContributor;
	
	/**
	 * Méthode à éxécuter avant la méthode principale du contrôleur
	 * @return void
	 */
	public function before() : void
	{
		parent::before();
		
		$this->_retrieveContributor();
	}
	
	/**
	 * Ajout d'un contributeur au livre
	 * @return void
	 */
	public function add() : void
	{
		$inputs = $this->request()->inputs();
		$contributorType = $this->request()->parameter('contributorType');
		
		$form = AddContributorForm::factory([
			'book' => $this->_book,
			'data' => $inputs,
			'contributor_type' => strtoupper($contributorType),
		]);
		
		$form->process();
		
		$this->_response_data = [
			'status' => ($form->success()) ? self::STATUS_SUCCESS : self::STATUS_ERROR,
			'data' => $form->response(),
		];
	}
	
	/**
	 * Suppression d'un contributeur au livre
	 *  @return void
	 */
	public function delete() : void
	{
		$this->_response_data = [
			'status' => self::STATUS_SUCCESS,
			'data' => $this->_contributor->delete(),
		];
	}
	
}