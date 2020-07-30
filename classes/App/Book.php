<?php

/**
 * Gestion d'un livre du site
 */

namespace App;

use Root\Route;
/***/
use App\Collection\BookListCollection;

class Book extends BaseBook {
	
	private ?array $_book_list = NULL;
	
	/**************************************************************************/
	
	/* LISTES */
	
	/**
	 * Listes auxquelles le livre appartient pour l'utilisateur connecté
	 * @param array Les listes à affecter
	 * @return array 
	 */
	public function bookLists(array $bookList = NULL) : array
	{
		if($bookList !== NULL)
		{
			$this->_book_list = $bookList;
		}
		elseif($this->_book_list === NULL)
		{
			$reader = User::current();
			if($reader === NULL)
			{
				exception('Vous devez être connecté.', 401);
			}
			$this->_book_list = BookListCollection::factory()->book($this)->reader($reader)->get();
		}
		return $this->_book_list;
	}
	
	/**
	 * Retourne si le livre est dans la liste de l'utilisateur
	 * @param string $list
	 * @return bool
	 */
	public function inList(string $list) : bool
	{
		$found = FALSE;
		$bookLists = $this->bookLists();
		foreach($bookLists as $bookList)
		{
			
			if($bookList->type == $list)
			{
				return TRUE;
			}
		}
		
		return $found;
	}
	
	/**
	 * Ajoute le livre à la liste de lecture de l'utilisateur
	 * @param string $list
	 * @return bool
	 */
	public function addListTo(string $list) : bool
	{
		if($this->inList($list))
		{
			return TRUE;
		}
		
		$bookList = BookList::factory([
			'book' => $this->id,
			'reader' => User::current()->id,
			'type' => $list,
		]);
		
		return $bookList->create();
	}
	
	/**
	 * Retire le livre de la liste de lecture de l'utilisateur
	 * @param string $list
	 * @return bool
	 */
	public function deleteListFrom(string $list) : bool
	{
		if(! $this->inList($list))
		{
			return TRUE;
		}
		
		$bookList = BookList::factory([
			'book' => $this->id,
			'reader' => User::current()->id,
			'type' => $list,
		]);
		
		return $bookList->delete();
	}
	
	/**************************************************************************/
	
	/* URI */
	
	/**
	 * Retourne l'URI de la liste des livres
	 * @return string
	 */
	public static function listUri() : string
	{
		return Route::retrieve('home')->uri();
	}
	
	/**
	 * Retourne l'URI d'ajout du livre à la liste en paramètre
	 * @param string $list
	 * @return string
	 */
	public function addToListUri(string $list) : string
	{
		return Route::retrieve('books.list.add')->uri([
			'bookId' => $this->id,
			'bookList' => strtolower($list),
		]);
	}
	
	/**
	 * Retourne l'URI de suppression du livre de la liste en paramètre
	 * @param string $list
	 * @return string
	 */
	public function deleteFromListUri(string $list) : string
	{
		return Route::retrieve('books.list.delete')->uri([
			'bookId' => $this->id,
			'bookList' => strtolower($list),
		]);
	}
	
	/**************************************************************************/
	
}