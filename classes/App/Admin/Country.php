<?php

/**
 * Gestion d'un pays pour le panneau d'administration
 */

namespace App\Admin;

use App\{ Country as CountryModel };
use App\Admin\Collection\CountryCollection as Collection;
use App\Admin\Traits\Models\WithAutocomplete;
/***/
use Root\{ Route };

class Country extends CountryModel {
	
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
		return Collection::factory()->search($search)->orderBy(Collection::ORDER_BY_NAME)->get(10);
	}
	
	/**
	 * Retourne les données de formatage pour l'affichage d'un résultat de la recherche par autocomplètion
	 * @return array
	 */
	public function adminSearchData() : array
	{
		return [
			'value' => $this->code,
			'text' => $this->name,
		];
	}
	
	/**
	 * Texte affiché dans le champs d'autocomplètion
	 * @return string
	 */
	public function adminSearchTitle() : string
	{
		return $this->name;
	}
	
	/**
	 * Texte affiché lorsque le champs d'authocomplètion est vide
	 * @return string
	 */
	public static function adminPlaceholderSearch() : string
	{
		return 'Rechercher un pays.';
	}
	
	/*********************************************************************/
	
	/* URI */
	
	/**
	 * Retourne l'URI de la liste des pays du panneau d'administration
	 * @return string
	 */
	public static function adminListUri() : string
	{
		return Route::retrieve('admin.countries.list')->uri();
	}
	
	/**
	 * Retourne l'URI de création d'un pays
	 * @return string
	 */
	public static function adminAddUri() : string
	{
		return Route::retrieve('admin.countries.add')->uri();
	}
	
	/**
	 * Retourne l'URI de modification d'un pays
	 * @return string
	 */
	public function adminEditUri() : string
	{
		return Route::retrieve('admin.countries.edit')->uri([
			'countryCode' => strtolower($this->code),
		]);
	}
	
}