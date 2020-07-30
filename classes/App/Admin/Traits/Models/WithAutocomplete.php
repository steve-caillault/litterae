<?php

/**
 * Trait gérant la recherche par autocomplètion
 */

namespace App\Admin\Traits\Models;

use Root\{ Route };

trait WithAutocomplete {
	
	/**
	 * Retourne l'URI de la recherche par autocomplètion
	 * @return string
	 */
	public static function adminSearchUri() : string
	{
		return Route::retrieve('admin.ajax.search')->uri();
	}
	
	/**
	 * Résultat de la recherche par autocomplètion
	 * @param string $search
	 * @param int $limit
	 * @return array
	 */
	abstract public static function adminSearch(string $search, int $limit) : array;  
	
	/**
	 * Texte affiché dans le champs d'autocomplètion lorsqu'un modèle est édité
	 * @return string
	 */
	abstract public function adminSearchTitle() : string;
	
	/**
	 * Texte affiché lorsque le champs d'authocomplètion est vide
	 * @return string
	 */
	abstract public static function adminPlaceholderSearch() : string;
	
	/**
	 * Retourne les données de formatage pour l'affichage d'un résultat de la recherche par autocomplètion
	 * @return array
	 */
	abstract public function adminSearchData() : array;
	
	/**
	 * Retourne les données d'un champs d'autocomplètion
	 * @param string $fieldValue
	 * @return array
	 */
	public static function adminSearchField(?string $fieldValue) : array
	{
		$model = ($fieldValue !== NULL) ? static::factory($fieldValue) : NULL;
		return [
			'request_url' => getURL(static::adminSearchUri()),
			'text_value' => ($model !== NULL) ? $model->adminSearchTitle() : NULL,
			'placeholder' => static::adminPlaceholderSearch(),
			'model' => static::class,
		];
	}
	
}