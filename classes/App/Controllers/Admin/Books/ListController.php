<?php

/**
 * Liste des livres
 */

namespace App\Controllers\Admin\Books;

use App\Forms\Admin\Books\SearchForm;
use App\Admin\HTML\Collection\BookCollectionHTML as CollectionHTML;

class ListController extends CreateOrEditController {
	
	public function index() : void
	{
		// Gestion du formulaire de recherche
		$inputs = $this->request()->inputs();
		$searchForm = SearchForm::factory([
			'data' => $inputs,
		]);
		if(count($inputs) > 0)
		{
			$searchForm->process();
		}
		
		$form = $searchForm->render();
		$collection = CollectionHTML::factory([
			'search' => $searchForm->search(),
		])->render();
		
		// Contenu
		$this->_main_content = $form . $collection;
	}
	
	/********************************************************/
	
	/**
	 * Gestion du titre de la page
	 * @return void
	 */
	protected function _managePageTitle() : void
	{
		$this->_page_title = 'Liste des livres';
		$this->_head_title = $this->_page_title;
	}
	
	/********************************************************/
	
}