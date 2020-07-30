<?php

/**
 * Formulaire de recherche de livre avec filtre d'éditeur
 */

namespace App\Forms\Search;

use Root\{ Route };
/***/
use App\{ Editor };

class SearchBookWithFilterEditor extends SearchBookForm {
	
	/**
	 * Editeur dont les livres sont filtrés
	 * @var Editor
	 */
	private Editor $_editor;
	
	/****************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION  */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
		$editor = getArray($params, 'editor');
		if($editor === NULL OR ! $editor instanceof Editor)
		{
			exception('Editeur incorrect.');
		}
		$this->_editor = $editor;
		parent::__construct($params);
	}
	
	/****************************************************************************/
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Route::retrieve('editors.item')->uri([
			'editorId' => $this->_editor->id,
		]));
	}
	
	/****************************************************************************/
	
}