<?php

/**
 * Gestion du menu des pages gérant les pays
 */

namespace App\Admin\HTML\Menu;

use Root\{ Instanciable, Request };
/***/
use App\Admin\{ Country };
use App\HTML\Menu\MenuHTML;

class CountryMenuHTML extends Instanciable {
	
	/**
	 * Pays à gérer
	 * @var Country
	 */
	private ?Country $_country = NULL;
	
	/**********************************************************/
	
	/**
	 * Constructeur
	 * @param array $params : array(
	 * 		'country' => <Country>,
	 * 		'state' => <CountryState>,
	 * 		'city' => <City>,
	 * )
	 */
	protected function __construct(array $params)
	{
		// Affectation et vérification du pays
		$this->_country = getArray($params, 'country', $this->_country);
		if($this->_country !== NULL AND ! ($this->_country instanceof Country))
		{
			exception('Pays incorrect.');
		}
	}
	
	/**********************************************************/
	
	/**
	 * Retourne le menu des pages liées aux pays
	 * @return MenuHTML
	 */
	public function get() : MenuHTML
	{
		$currentRoute = Request::current()->route()->name();
		
		$menu = MenuHTML::factory(MenuHTML::TYPE_SECONDARY)->addItem('country-list', [
			'label' => 'Liste des pays',
			'href' 	=> Country::adminListUri(),
			'class' => ($currentRoute == 'admin.countries.list') ? 'selected' : '',
			'title' => 'Consulter la liste des pays.',
		])->addItem('country-add', [
			'label'	=> 'Ajouter un pays',
			'href'	=> Country::adminAddUri(),
			'class' => ($currentRoute == 'admin.countries.add') ? 'selected' : '',
			'title'	=> 'Ajouter un pays.',
		]);
		
		if($this->_country !== NULL)
		{
			$menu->addItem('country-edit', [
				'label' => strtr('Modifier le pays', [ ':name' => $this->_country->code, ]),
				'href' => $this->_country->adminEditUri(),
				'class' => ($currentRoute == 'admin.countries.edit') ? 'selected' : '',
				'title' => strtr('Modifier le pays :name.', [ ':name' => $this->_country->name, ]),
			]);
		}
		
		return $menu;
	}
	
	/**********************************************************/
	
}