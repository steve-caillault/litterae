<?php

/**
 * Gestion d'une collection d'un éditeur depuis le panneau d'administration
 */

namespace App\Admin;

use Root\Route;
use App\Collection as CollectionModel;
use App\Admin\Collection\CollectionEditorCollection;
use App\Admin\Traits\Models\WithAutocomplete;

class Collection extends CollectionModel {
	
	use WithAutocomplete;
	
	/**
	 * Classe de l'objet éditeur à utiliser
	 */
	protected static string $_editor_class = Editor::class;
	
	/*********************************************************************/
	
	/* AUTOCOMPLETION */
	
	/**
	 * Résultat de la recherche par autocomplètion
	 * @param string $search
	 * @param int $limit
	 * @return array
	 */
	public static function adminSearch(string $search, int $limit) : array
	{
		return CollectionEditorCollection::factory()->search($search)->orderBy(CollectionEditorCollection::ORDER_BY_NAME)->get(10);
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
		return 'Rechercher une collection.';
	}
	
	/*********************************************************************/
	
	/* URI */
	
	/**
	 * Retourne l'URI de la liste des collections du panneau d'administration
	 * @return string
	 */
	public static function adminListUri() : string
	{
		return Route::retrieve('admin.collections.list')->uri();
	}
	
	/**
	 * Retourne l'URI de l'ajout d'une collection
	 * @return string
	 */
	public static function adminAddUri() : string
	{
		return Route::retrieve('admin.collections.add')->uri();
	}
	
	/**
	 * Retourne l'URI de modification d'une collection
	 * @return string
	 */
	public function adminEditUri() : string
	{
		return Route::retrieve('admin.collections.edit')->uri([
			'collectionId' => $this->id,
		]);
	}
	
	/*********************************************************************/
	
}