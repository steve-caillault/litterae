<?php

/**
 * Gestion des listes auxquelles appartient un livre
 */

namespace App\Controllers\Site\Books;

use Root\{ Request };
/***/
use App\Controllers\Traits\WithBook;
use App\Controllers\Site\Traits\WithLoggedUser;
use App\Controllers\AjaxController as Controller;
/***/
use App\BookList;

class ListsController extends Controller {
	
	use WithBook, WithLoggedUser;
	
	/**
	 * Vrai si la page demande un utilisateur connecté
	 * @var bool
	 */
	protected bool $_required_user = TRUE;
	
	/**
	 * Liste à gérer
	 * @var string
	 */
	private string $_book_list;
	
	/**************************************************************/
	
	/**
	 * 
	 */
	public function before() : void
	{
		parent::before();
		
		$this->_retrieveUser();
		$this->_retrieveBook();
		
		// Récupération de la liste à gérer
		$bookList = strtoupper(Request::current()->parameter('bookList'));
		if(! in_array($bookList, BookList::allowedTypes()))
		{
			exception('Liste incorrecte.', 404);
		}
		$this->_book_list = $bookList;
	}
	
	/**
	 * Ajoute le livre à la liste de lecture
	 * @return void
	 */
	public function add() : void
	{
		$success = $this->_book->addListTo($this->_book_list);
		$this->_response_data = [
			'status' => self::STATUS_SUCCESS,
			'data' => $success,
		];
	}
	
	/**
	 * Supprime le livre à la liste de lecture
	 * @return void
	 */
	public function delete() : void
	{
		$success = $this->_book->deleteListFrom($this->_book_list);
		$this->_response_data = [
			'status' => self::STATUS_SUCCESS,
			'data' => $success,
		];
	}
	
	/**************************************************************/
}
	