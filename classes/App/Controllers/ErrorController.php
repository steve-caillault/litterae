<?php

/**
 * Page d'erreur
 */

namespace App\Controllers;

use App\Controllers\HTML\ContentController;

class ErrorController extends ContentController {
	
	public function index() : void
	{
		$inputs = $this->request()->post();
		$message = getArray($inputs, 'message', 'Une erreur s\'est produite.');
		
		$this->_main_content = '<p>' . $message . '</p>';
	}
	
	/**********************************************************/
	
	/**
	 * Gestion du titre de la page
	 * @return void
	 */
	protected function _managePageTitle() : void
	{
		$this->_page_title = 'Erreur';
	}
	
	/**********************************************************/
	
}