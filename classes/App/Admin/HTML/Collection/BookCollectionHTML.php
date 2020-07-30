<?php

/**
 * Gestion HTML d'une liste de livres
 */

namespace App\Admin\HTML\Collection;

use Root\{ HTML };
/***/
use App\{ Model };
use App\HTML\Collection\CollectionHTML;
use App\Collection\{ Collection };
/***/
use App\Admin\Book;
use App\Admin\Collection\BookCollection;

class BookCollectionHTML extends CollectionHTML {
	
	/**
	 * Type de tri
	 * @var string
	 */
	protected ?string $_order_by = BookCollection::ORDER_BY_TITLE;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = BookCollection::DIRECTION_ASC;
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : Collection
	{
		return BookCollection::factory();
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
		$title = HTML::anchor($model->adminEditUri(), $model->title, [
			'title' => strtr('Modifier le livre :title.', [ ':title' => $model->title, ]),
		]);
		
		$editor = $model->editor();
		$editorName = HTML::anchor($editor->adminEditUri(), $editor->name, [
			'title' => strtr('Modifier l\'éditeur :name.', [ ':name' => $editor->name ]),
		]);
		
		return [
			'attributes' => HTML::attributes([ 'class' => 'line', ]),
			'title' => $title,
			'editor' => $editorName,
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		$anchor = HTML::anchor(Book::adminAddUri(), 'ici', [
			'title' => 'Ajouter un livre en cliquant ici.',
		]);
		
		return strtr('Aucun livre n\'a été trouvé. Cliquez :anchor pour en ajouter un.', [
			':anchor' => $anchor,
		]);
	}
	
	/**
	 * Retourne les clés des champs à afficher
	 * @return array
	 */
	protected function _fields() : array
	{
		return [ 'title', 'editor', ];
	}
	
	/*****************************************************************/
	
}