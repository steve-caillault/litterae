<?php

/**
 * Gestion d'un illustrateur
 */

namespace App;

use Root\Route;

class IllustratorContributor extends Contributor {
	
	/**
	 * Retourne l'URI de la liste des livres du contributeur
	 * @return string
	 */
	public function booksUri() : string
	{
		return Route::retrieve('illustrators.item')->uri([
			'illustratorId' => $this->person,
		]);
	}
}