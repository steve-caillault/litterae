<?php

/**
 * Gestion du menu des pages gérant les éditeurs
 */

namespace App\Admin\HTML\Menu;

use Root\{ Instanciable, Request };
/***/
use App\Admin\{ Editor };
use App\HTML\Menu\MenuHTML;

class EditorMenuHTML extends Instanciable {
	
	/**
	 * Editeur à gérer
	 * @var Editor
	 */
	private ?Editor $_editor = NULL;
	
	/**********************************************************/
	
	/**
	 * Constructeur
	 * @param array $params : array(
	 * 		'editor' => <Editor>,
	 * )
	 */
	protected function __construct(array $params)
	{
		// Affectation et vérification de l'éditeur
		$this->_editor = getArray($params, 'editor', $this->_editor);
		if($this->_editor !== NULL AND ! ($this->_editor instanceof Editor))
		{
			exception('Editeur incorrect.');
		}
	}
	
	/**********************************************************/
	
	/**
	 * Retourne le menu des pages liées aux éditeurs
	 * @return MenuHTML
	 */
	public function get() : MenuHTML
	{
		$currentRoute = Request::current()->route()->name();
		
		$menu = MenuHTML::factory(MenuHTML::TYPE_SECONDARY)->addItem('editor-list', [
			'label' => 'Liste des éditeurs',
			'href' 	=> Editor::adminListUri(),
			'class' => ($currentRoute == 'admin.editors.list') ? 'selected' : '',
			'title' => 'Consulter la liste des éditeurs.',
		])->addItem('editor-add', [
			'label'	=> 'Ajouter un éditeur',
			'href'	=> Editor::adminAddUri(),
			'class' => ($currentRoute == 'admin.editors.add') ? 'selected' : '',
			'title'	=> 'Ajouter un éditeur.',
		]);
		
		if($this->_editor !== NULL)
		{
			$menu->addItem('editor-edit', [
				'label' => strtr('Modifier l\'éditeur :name', [ ':name' => $this->_editor->name, ]),
				'href' => $this->_editor->adminEditUri(),
				'class' => ($currentRoute == 'admin.editors.edit') ? 'selected' : '',
				'title' => strtr('Modifier l\'éditeur :name.', [ ':name' => $this->_editor->name, ]),
			]);
		}
		
		return $menu;
	}
	
	/**********************************************************/
	
}