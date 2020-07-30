<?php

/**
 * Gestion d'une personne pour le panneau d'administration
 */

namespace App\Admin;

use App\{ BasePerson as PersonModel };
use App\Admin\Traits\Models\WithAutocomplete;
use App\Admin\Collection\PersonCollection;
/***/
use Root\{ Route };

class Person extends PersonModel {
	
	use WithAutocomplete;
	
	/* AUTOCOMPLETION */
	
	/**
	 * Résultat de la recherche par autocomplètion
	 * @param string $search
	 * @param int $limit
	 * @return array
	 */
	public static function adminSearch(string $search, int $limit) : array
	{
		return PersonCollection::factory()->search($search)->orderBy(PersonCollection::ORDER_BY_NAME)->get(10);
	}
	
	/**
	 * Retourne les données de formatage pour l'affichage d'un résultat de la recherche par autocomplètion
	 * @return array
	 */
	public function adminSearchData() : array
	{
		return [
			'value' => $this->id,
			'text' => $this->fullName(),
		];
	}
	
	/**
	 * Texte affiché dans le champs d'autocomplètion
	 * @return string
	 */
	public function adminSearchTitle() : string
	{
		return $this->fullName();
	}
	
	/**
	 * Texte affiché lorsque le champs d'authocomplètion est vide
	 * @return string
	 */
	public static function adminPlaceholderSearch() : string
	{
		return 'Rechercher une personne.';
	}
	
	/* URI */
	
	/**
	 * Retourne l'URI de la liste des personnes du panneau d'administration
	 * @return string
	 */
	public static function adminListUri() : string
	{
		return Route::retrieve('admin.persons.list')->uri();
	}
	
	/**
	 * Retourne l'URI de création d'une personne
	 * @return string
	 */
	public static function adminAddUri() : string
	{
		return Route::retrieve('admin.persons.add')->uri();
	}
	
	/**
	 * Retourne l'URI de modification d'une personnes
	 * @return string
	 */
	public function adminEditUri() : string
	{
		return Route::retrieve('admin.persons.edit')->uri([
			'personId' => $this->id,
		]);
	}
	
}