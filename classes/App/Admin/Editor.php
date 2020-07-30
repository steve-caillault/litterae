<?php

/**
 * Gestion d'un éditeur depuis le panneau d'administration
 */

namespace App\Admin;

use Root\{ Route };
use App\{
	Editor as EditorModel
};
use App\Admin\Collection\EditorCollection as Collection;
use App\Admin\Traits\Models\WithAutocomplete;

class Editor extends EditorModel {
	
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
			'value' => $this->id,
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
		return 'Rechercher un éditeur.';
	}
	
	/*********************************************************************/
	
	/* URI */
	
	/**
	 * Retourne l'URI de la liste des éditeur du panneau d'administration
	 * @return string
	 */
	public static function adminListUri() : string
	{
		return Route::retrieve('admin.editors.list')->uri();
	}
	
	/**
	 * Retourne l'URI d'ajout d'un éditeur
	 * @return string
	 */
	public static function adminAddUri() : string
	{
		return Route::retrieve('admin.editors.add')->uri();
	}
	
	/**
	 * Retourne l'URI de modification d'un éditeur
	 * @return string
	 */
	public function adminEditUri() : string
	{
		return Route::retrieve('admin.editors.edit')->uri([
			'editorId' => $this->id,
		]);
	}
	
	/*********************************************************************/
	
}