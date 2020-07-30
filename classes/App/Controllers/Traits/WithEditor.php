<?php

/**
 * Trait utilisé pour les contrôleurs utilisant un éditeur
 */

namespace App\Controllers\Traits;

use App\{ Editor };

trait WithEditor {
	
	/**
	 * Editeur à gérer
	 * @var Editor
	 */
	protected ?Editor $_editor = NULL;
	
	/**
	 * Affecte l'éditeur de la requête HTTP
	 * @return void
	 */
	protected function _retrieveEditor() : void
	{
		$editorId = getArray($this->request()->parameters(), 'editorId');
		if($editorId !== NULL)
		{
			$this->_editor = Editor::factory($editorId);
			if($this->_editor === NULL)
			{
				exception('L\'éditeur n\'existe pas.', 404);
			}
		}
	}
	
}