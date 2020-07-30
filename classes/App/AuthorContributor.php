<?php

/**
 * Gestion d'un auteur
 */

namespace App;

use Root\Route;

class AuthorContributor extends Contributor {
	
	/**
	 * Retourne l'URI de la liste des livres du contributeur
	 * @return string
	 */
	public function booksUri() : string 
	{
		return Route::retrieve('authors.item')->uri([
			'authorId' => $this->person,
		]);
	}
}