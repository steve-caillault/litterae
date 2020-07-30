<?php

/**
 * Gestion d'une personne contribuant à l'écriture d'un livre (auteur ou traducteur)
 */

namespace App;

use App\Traits\Models\WithBook;

abstract class BaseContributor extends WithPerson {
	
	use WithBook;
	
	/**
	 * Classe de l'objet livre à utiliser
	 * @var string
	 */
	protected static string $_book_class = Book::class;
	
	/**************************************************************/
	
	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'books_contributors';
	
	/**
	 * Clé primaire
	 * @var string
	 */
	protected static string $_primary_key = 'book|person|type';
	
	/**
	 * Vrai si la clé primaire est un auto-incrément
	 * @var bool
	 */
	protected static bool $_autoincrement = FALSE;
	
	/**************************************************************/

}