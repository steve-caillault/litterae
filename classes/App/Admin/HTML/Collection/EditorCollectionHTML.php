<?php

/**
 * Gestion HTML d'une liste d'éditeur
 */

namespace App\Admin\HTML\Collection;

use Root\{ HTML };
/***/
use App\{ Model };
use App\HTML\Collection\CollectionHTML;
use App\Collection\{ Collection };
/***/
use App\Admin\Editor;
use App\Admin\Collection\EditorCollection;

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
	protected string $_direction = EditorCollection::DIRECTION_ASC;
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : Collection
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
		$name = HTML::anchor($model->adminEditUri(), $model->name, [
			'title' => strtr('Modifier l\'éditeur :name.', [ ':name' => $model->name, ]),
		]);
		
		return [
			'attributes' => HTML::attributes([ 'class' => 'line', ]),
			'name' => $name,
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		$anchor = HTML::anchor(Editor::adminAddUri(), 'ici', [
			'title' => 'Ajouter un éditeur en cliquant ici.',
		]);
		
		return strtr('Aucun éditeur n\'a été trouvé. Cliquez :anchor pour en ajouter un.', [
			':anchor' => $anchor,
		]);
	}
	
	/**
	 * Retourne les clés des champs à afficher
	 * @return array
	 */
	protected function _fields() : array
	{
		return [ 'name', ];
	}
	
	/*****************************************************************/
	
}