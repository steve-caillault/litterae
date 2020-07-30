<?php

/**
 * Gestion du menu des pages gérant les livres
 */

namespace App\Admin\HTML\Menu;

use Root\{ Instanciable, Request };
/***/
use App\Admin\{ Book };
use App\HTML\Menu\MenuHTML;

class BookMenuHTML extends Instanciable {
	
	/**
	 * Livre à gérer
	 * @var Book
	 */
	private ?Book $_book = NULL;
	
	/**********************************************************/
	
	/**
	 * Constructeur
	 * @param array $params : array(
	 * 		'book' => <Book>,
	 * )
	 */
	protected function __construct(array $params)
	{
		// Affectation et vérification du livre
		$book = getArray($params, 'book');
		if($book !== NULL AND ! ($book instanceof Book))
		{
			exception('Livre incorrect.');
		}
		
		$this->_book = $book;
	}
	
	/**********************************************************/
	
	/**
	 * Retourne le menu des pages liées aux livres
	 * @return MenuHTML
	 */
	public function get() : MenuHTML
	{
		$currentRoute = Request::current()->route()->name();
		
		$menu = MenuHTML::factory(MenuHTML::TYPE_SECONDARY)->addItem('book-list', [
			'label' => 'Liste des livres',
			'href' 	=> Book::adminListUri(),
			'class' => ($currentRoute == 'admin.books.list') ? 'selected' : '',
			'title' => 'Consulter la liste des livres.',
		])->addItem('book-add', [
			'label'	=> 'Ajouter un livre',
			'href'	=> Book::adminAddUri(),
			'class' => ($currentRoute == 'admin.books.add') ? 'selected' : '',
			'title'	=> 'Ajouter un livre.',
		]);
		
		if($this->_book !== NULL)
		{
			$menu->addItem('book-edit', [
				'label' => 'Modifier le livre',
				'href' => $this->_book->adminEditUri(),
				'class' => ($currentRoute == 'admin.books.edit') ? 'selected' : '',
				'title' => strtr('Modifier le livre :title.', [ ':title' => $this->_book->title, ]),
			]);
		}
		
		return $menu;
	}
	
	/**********************************************************/
	
}