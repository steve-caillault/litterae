<?php

/**
 * Gestion du menu des pages gérant les collections
 */

namespace App\Admin\HTML\Menu;

use Root\{ Instanciable, Request };
/***/
use App\Admin\{ Collection };
use App\HTML\Menu\MenuHTML;

class CollectionMenuHTML extends Instanciable {
	
	/**
	 * Collection à gérer
	 * @var Collection
	 */
	private ?Collection $_collection = NULL;
	
	/**********************************************************/
	
	/**
	 * Constructeur
	 * @param array $params : array(
	 * 		'collection' => <Collection>,
	 * )
	 */
	protected function __construct(array $params)
	{
		// Affectation et vérification de la collection
		$this->_collection = getArray($params, 'collection', $this->_collection);
		if($this->_collection !== NULL AND ! ($this->_collection instanceof Collection))
		{
			exception('Collection incorrecte.');
		}
	}
	
	/**********************************************************/
	
	/**
	 * Retourne le menu des pages liées aux collections
	 * @return MenuHTML
	 */
	public function get() : MenuHTML
	{
		$currentRoute = Request::current()->route()->name();
		
		$menu = MenuHTML::factory(MenuHTML::TYPE_SECONDARY)->addItem('collection-list', [
			'label' => 'Liste des collections',
			'href' 	=> Collection::adminListUri(),
			'class' => ($currentRoute == 'admin.collections.list') ? 'selected' : '',
			'title' => 'Consulter la liste des collections.',
		])->addItem('collection-add', [
			'label'	=> 'Ajouter une collection',
			'href'	=> Collection::adminAddUri(),
			'class' => ($currentRoute == 'admin.collections.add') ? 'selected' : '',
			'title'	=> 'Ajouter une collection.',
		]);
		
		if($this->_collection !== NULL)
		{
			$menu->addItem('collection-edit', [
				'label' => strtr('Modifier la collection :name', [ ':name' => $this->_collection->name, ]),
				'href' => $this->_collection->adminEditUri(),
				'class' => ($currentRoute == 'admin.collections.edit') ? 'selected' : '',
				'title' => strtr('Modifier la collection :name.', [ ':name' => $this->_collection->name, ]),
			]);
		}
		
		return $menu;
	}
	
	/**********************************************************/
	
}