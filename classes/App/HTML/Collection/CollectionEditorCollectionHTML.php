<?php

/**
 * Gestion HTML d'une liste de collections
 */

namespace App\HTML\Collection;

use Root\{ Route, HTML };
/***/
use App\{ Model };
use App\Collection\{ CollectionEditorCollection as Collection };

class CollectionEditorCollectionHTML extends CollectionHTML {
	
	/**
	 * Type de tri
	 * @var string
	 */
    protected ?string $_order_by = Collection::ORDER_BY_NAME;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = Collection::DIRECTION_ASC;
	
	/**
	 * Nom de la vue utilisée pour l'affichage
	 * @var string
	 */
	protected string $_view_name = 'items/collections';
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : Collection
	{
	    return Collection::factory();
	}
	
	/*****************************************************************/
	
	/* METHODES DE RENDU */
	
	/**
	 * Formatage des données d'un modèle
	 * @param Model $model
	 * @param mixed Index dans le tableau de la liste
	 * @return array
	 */
	protected function _formatModelData(Model $model, $index) : array
	{
		$collectionUri = Route::retrieve('collections.item')->uri([
			'collectionId' => $model->id,
		]);
		
		$collectionAnchor = HTML::anchor($collectionUri, $model->name, [
			'title' => strtr('Consulter la liste des livres de la collection :name.', [
				':name' => $model->name,
			]),
		]);
		
		return [
			'name' => $collectionAnchor,
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		return 'Aucune collection n\'a été trouvé.';
	}
	
	/**
	 * Retourne les clés des champs à afficher
	 * @return array
	 */
	protected function _fields() : array
	{
		return [];
	}
	
	/*****************************************************************/
	
}