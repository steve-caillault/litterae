<?php

/**
 * Gestion d'une collection de liste de livres
 */

namespace App\Collection;

use App\{ Book, BookList, Reader };

class BookListCollection extends Collection {
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = BookList::class;
	
	/**********************************************************/
	
	/* FILTRES */
	
	/**
	 * Filtre le lecteur
	 * @param Reader $reader
	 * @return self
	 */
	public function reader(Reader $reader) : self
	{
		$this->_query->where($this->_table . '.reader', '=', $reader->id);
		return $this;
	}
	
	/**
	 * Filtre le livre
	 * @param Book $book
	 * @return self
	 */
	public function book(Book $book) : self
	{
		$this->_query->where($this->_table . '.book', '=', $book->id);
		return $this;
	}
	
	/**
	 * Filtre les identifiants des livres
	 * @param array $bookIds
	 * @return self
	 */
	public function bookIds(array $bookIds) : self
	{
		$this->_query->where($this->_table . '.book', 'IN', $bookIds);
		return $this;
	}
	
	/**********************************************************/
	
}