<?php

/**
 * Menu principal du site
 */

namespace App\HTML\Menu;

use Root\{ Instanciable, View, Request, Route };
/***/
use App\{ Book, BookList };

class MainMenuHTML extends Instanciable
{
	/**
	 * Menu du site
	 * @var MenuHTML
	 */
    private ?MenuHTML $_menu = NULL;
	
	/****************************************************************************************************************************/
	
	/**
	 * Constructeur
	 * Initialise le menu en affectant tous les éléments
	 */
	protected function __construct()
	{
		$booksBaseUri = Book::listUri();
		$authorsBaseUri = Route::retrieve('authors')->uri();
		$currentRouteName = Request::current()->route()->name();
		
		$this->_menu = MenuHTML::factory(MenuHTML::TYPE_PRIMARY)->addItem('books', [
			'class' => ($currentRouteName == 'home') ? 'selected' : NULL,
			'label' => 'Livres',
			'href' => $booksBaseUri,
			'title' => 'Consulter la liste des livres.',
		])->addItem('authors', [
			'class' => (strpos($currentRouteName, 'authors') !== FALSE) ? 'selected' : NULL,
			'label' => 'Auteurs',
			'href' => $authorsBaseUri,
			'title' => 'Consulter la liste des auteurs.',
		])/*->addItem('editors', [
			'class' => (strpos($currentRouteName, 'editors') !== FALSE) ? 'selected' : NULL,
			'label' => 'Editeurs',
			'href' => Route::retrieve('editors')->uri(),
			'title' => 'Consulter la liste des éditeurs.',
		])->addItem('collections', [
			'class' => (strpos($currentRouteName, 'collections') !== FALSE) ? 'selected' : NULL,
			'label' => 'Collections',
			'href' => Route::retrieve('collections')->uri(),
			'title' => 'Consulter la liste des collections.',
		])*/;
		
		
		// Gestion des listes de livres
		$typesFormatted = [
			BookList::TYPE_OWNERSHIP => 'possesions',
			BookList::TYPE_READING => 'lectures',
			BookList::TYPE_WISH => 'souhaits',
			
		];
		$bookLists = BookList::allowedTypes();
		$currentList = getArray(Request::current()->query(), 'book-list-type');
		foreach($bookLists as $type)
		{
			$menuKey = 'books::list-' . strtolower($type);
			
			$selected = (strtoupper($currentList) == $type);
			
			$label = strtr('Liste des :type', [ 
				':type' => getArray($typesFormatted, $type),
			]);
			
			$uri = $booksBaseUri;
			if(! $selected)
			{
				$uri = $booksBaseUri . '?' . http_build_query([
					'book-list-type' => strtolower($type),
				]);
			}
			
			$alt = strtr('Filtre la liste des livres :type.', [
				':type' => translate(strtolower($type), [ 'count' => 2, ]),
			]);
			
			$this->_menu->addItem($menuKey, [
				'class' => ($selected) ? 'selected' : NULL,
				'label' => $label,
				'href' => $uri,
				'title' => $alt,
			]);
		}
		
		// Liste des auteurs suivis
		$filterAuthorsFollowed = getArray(Request::current()->query(), 'authors-followed');
		$this->_menu->addItem('authors::list-followed', [
			'class' => ($filterAuthorsFollowed) ? 'selected' : NULL,
			'label' => 'Auteurs suivis',
			'href' => $authorsBaseUri . '?' . http_build_query([
				'authors-followed' => TRUE,
			]),
			'title' => 'Consulter la liste des auteurs suivis.',
		]);
	}
	
	/****************************************************************************************************************************/
	
	/**
	 * Affichage
	 * @return string
	 */
	public function __toString() : string
	{
		return (string) $this->_menu;
	}
	
	/**
	 * Méthode de rendu
	 * @return View
	 */
	public function render() : ?View
	{
		if($this->_menu === NULL)
		{
			return NULL;
		}
		return $this->_menu->render();
	}
	
	/****************************************************************************************************************************/
}