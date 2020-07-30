<?php

/**
 * Gestion d'un traducteur
 */

namespace App;

use Root\Route;

class TranslatorContributor extends Contributor {
	
	/**
	 * Retourne l'URI de la liste des livres du contributeur
	 * @return string
	 */
	public function booksUri() : string
	{
		return Route::retrieve('translators.item')->uri([
			'translatorId' => $this->person,
		]);
	}
}