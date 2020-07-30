<?php

/**
 * Gestion du menu des pages gérant les personnes
 */

namespace App\Admin\HTML\Menu;

use Root\{ Instanciable, Request };
/***/
use App\Admin\{ Person };
use App\HTML\Menu\MenuHTML;

class PersonMenuHTML extends Instanciable {
	
	/**
	 * Personne à gérer
	 * @var Person
	 */
	private ?Person $_person = NULL;
	
	/**********************************************************/
	
	/**
	 * Constructeur
	 * @param array $params : array(
	 * 		'person' => <Person>,
	 * )
	 */
	protected function __construct(array $params)
	{
		// Affectation et vérification de la personne
		$this->_person = getArray($params, 'person', $this->_person);
		if($this->_person !== NULL AND ! ($this->_person instanceof Person))
		{
			exception('Personne incorrect.');
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
		
		$menu = MenuHTML::factory(MenuHTML::TYPE_SECONDARY)->addItem('person-list', [
			'label' => 'Liste des personnes',
			'href' 	=> Person::adminListUri(),
			'class' => ($currentRoute == 'admin.persons.list') ? 'selected' : '',
			'title' => 'Consulter la liste des personnes.',
		])->addItem('person-add', [
			'label'	=> 'Ajouter une personne',
			'href'	=> Person::adminAddUri(),
			'class' => ($currentRoute == 'admin.persons.add') ? 'selected' : '',
			'title'	=> 'Ajouter une personne.',
		]);
		
		if($this->_person !== NULL)
		{
			$menu->addItem('person-edit', [
				'label' => strtr('Modifier :name', [ ':name' => $this->_person->fullName(), ]),
				'href' => $this->_person->adminEditUri(),
				'class' => ($currentRoute == 'admin.persons.edit') ? 'selected' : '',
				'title' => strtr('Modifier :name.', [ ':name' => $this->_person->fullName(), ]),
			]);
		}
		
		return $menu;
	}
	
	/**********************************************************/
	
}