<?php

/**
 * Suppression de contributeurs d'un livre (auteurs, traducteurs)
 */

namespace App\Controllers\Admin\Books\Contributors;

use App\Controllers\Admin\HomeController;
use App\Controllers\Traits\WithContributor;

class DeleteController extends HomeController {
	
	use WithContributor;
	
	/***************************************************************/
	
	public function before() : void
	{
		parent::before();
		$this->_retrieveContributor();
	}
	
	/***************************************************************/
	
	public function index() : void 
	{
		$this->_contributor->delete();
		redirect($this->_book->adminEditUri());
	}
	
	/***************************************************************/
	
}