<?php

/**
 * Gestion HTML d'une liste d'éditeurs
 */

namespace App\HTML\Collection;

use Root\{ Route, HTML };
/***/
use App\{ Model };
use App\Collection\{ Collection, EditorCollection };

class EditorCollectionHTML extends CollectionHTML {
	
	/**
	 * Type de tri
	 * @var string
	 */
	protected ?string $_order_by = EditorCollection::ORDER_BY_NAME;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = Collection::DIRECTION_ASC;
	
	/**
	 * Nom de la vue utilisée pour l'affichage
	 * @var string
	 */
	protected string $_view_name = 'items/editors';
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : EditorCollection
	{
		return EditorCollection::factory();
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
		$editorUri = Route::retrieve('editors.item')->uri([
			'editorId' => $model->id,
		]);
		
		$editorAnchor = HTML::anchor($editorUri, $model->name, [
			'title' => strtr('Consulter la liste des livres de :name.', [
				':name' => $model->name,
			]),
		]);
		
		return [
			'name' => $editorAnchor,
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		return 'Aucun éditeur n\'a été trouvé.';
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