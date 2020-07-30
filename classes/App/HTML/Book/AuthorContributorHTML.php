<?php

/**
 * Gestion du HTML d'un auteur
 */

namespace App\HTML\Book;

use Root\Route;

class AuthorContributorHTML extends ContributorHTML {
	
	/**
	 * Vrai si on affiche un bouton pour l'abonnement
	 * @var bool
	 */
	protected bool $_with_subscription_button = TRUE;
	
	/**
	 * Retourne l'URI de la liste des livres du contributeur
	 * @return string
	 */
	public function booksUri() : string
	{
		return Route::retrieve('authors.item')->uri([
			'authorId' => $this->_person->id,
		]);
	}
	
}