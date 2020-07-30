<?php

/**
 * Classes pour les objets du modèle utilisant un livre
 */

namespace App\Traits\Models;

use App\BaseBook as Book;

trait WithBook {
	
	/**
	 * Identifiant du livre en base de données
	 * @var int
	 */
	public ?int $book = NULL;
	
	/**
	 * Livre
	 * @var Book
	 */
	private ?Book $_book = NULL;
	
	/**************************************************************/
	
	/**
	 * Retourne le livre
	 * @param Book $book Si renseigné, le livre à affecter
	 * @return Book
	 */
	public function book(?Book $book = NULL) : Book
	{
		if($book !== NULL)
		{
			$this->_book = $book;
			$this->book = $book->id;
		}
		elseif($this->_book === NULL AND $this->book !== NULL)
		{
			$this->_book = static::$_book_class::factory($this->book);
		}
		return $this->_book;
	}
	
	/**************************************************************/
	
}