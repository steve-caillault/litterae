<?php

/**
 * Gestion du HTML d'un traducteur
 */

namespace App\HTML\Book;

use Root\Route;

class TranslatorContributorHTML extends ContributorHTML {
	
	/**
	 * Retourne l'URI de la liste des livres du contributeur
	 * @return string
	 */
	public function booksUri() : string
	{
		return Route::retrieve('translators.item')->uri([
			'translatorId' => $this->_person->id,
		]);
	}
	
}