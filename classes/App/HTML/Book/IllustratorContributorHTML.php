<?php

/**
 * Gestion du HTML d'un illustrateur
 */

namespace App\HTML\Book;

use Root\Route;

class IllustratorContributorHTML extends ContributorHTML {
	
	/**
	 * Retourne l'URI de la liste des livres du contributeur
	 * @return string
	 */
	public function booksUri() : string
	{
		return Route::retrieve('illustrators.item')->uri([
			'illustratorId' => $this->_person->id,
		]);
	}
	
}