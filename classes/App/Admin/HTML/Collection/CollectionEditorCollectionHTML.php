<?php

/**
 * Gestion HTML d'une liste de collection
 */

namespace App\Admin\HTML\Collection;

use Root\{ HTML };
/***/
use App\{ Model };
use App\HTML\Collection\CollectionHTML;
use App\Collection\{ Collection };
/***/
use App\Admin\Collection as CollectionEditor;
use App\Admin\Collection\CollectionEditorCollection;

class CollectionEditorCollectionHTML extends CollectionHTML {
	
	/**
	 * Type de tri
	 * @var string
	 */
	protected ?string $_order_by = CollectionEditorCollection::ORDER_BY_NAME;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = CollectionEditorCollection::DIRECTION_ASC;
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : Collection
	{
		return CollectionEditorCollection::factory();
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
		$name = HTML::anchor($model->adminEditUri(), $model->name, [
			'title' => strtr('Modifier la collection :name.', [ ':name' => $model->name, ]),
		]);
		
		$editor = $model->editor();
		$editorName = HTML::anchor($editor->adminEditUri(), $editor->name, [
			'title' => strtr('Modifier l\'éditeur :name.', [ ':name' => $editor->name, ]),
		]);
		
		return [
			'attributes' => HTML::attributes([ 'class' => 'line', ]),
			'name' => $name,
			'editor' => $editorName,
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		$anchor = HTML::anchor(CollectionEditor::adminAddUri(), 'ici', [
			'title' => 'Ajouter une collection en cliquant ici.',
		]);
		
		return strtr('Aucune collection n\'a été trouvé. Cliquez :anchor pour en ajouter une.', [
			':anchor' => $anchor,
		]);
	}
	
	/**
	 * Retourne les clés des champs à afficher
	 * @return array
	 */
	protected function _fields() : array
	{
		return [ 'name', 'editor', ];
	}
	
	/*****************************************************************/
	
}