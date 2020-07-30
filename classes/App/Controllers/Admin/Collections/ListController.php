<?php

/**
 * Liste des collections
 */

namespace App\Controllers\Admin\Collections;

use App\Forms\Admin\Collections\SearchForm;
use App\Admin\HTML\Collection\CollectionEditorCollectionHTML as CollectionHTML;

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
		$this->_page_title = 'Liste des collections';
		$this->_head_title = $this->_page_title;
	}
	
	/********************************************************/
	
}