<?php

/**
 * Gestion d'une personne
 */

namespace App;

use Root\Route;

class Person extends BasePerson {

	/**
	 * Retourne l'URI d'abonnement au type en paramètre
	 * @param string $type
	 * @return string
	 */
	public function followUri(string $type) : string
	{
		return Route::retrieve('persons.follow')->uri([
			'personId' => $this->id,
			'contributorType' => strtolower($type),
		]);
	}
	
	/**
	 * Retourne l'URI de désabonnement au type en paramètre
	 * @param string $type
	 * @return string
	 */
	public function unfollowUri(string $type) : string
	{
		return Route::retrieve('persons.unfollow')->uri([
			'personId' => $this->id,
			'contributorType' => strtolower($type),
		]);
	}
	
	/**
	 * Retourne le contributeur du livre dont le type est en paramètre
	 * @param Book $book 
	 * @param string $type Type de contributeur
	 * @return Contributor
	 */
	public function contributor(Book $book, string $type) : Contributor
	{
		return Contributor::factory([
			'book' => $book->id,
			'person' => $this->id,
			'type' => $type,
		]);
	}
}