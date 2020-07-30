<?php

/**
 * Gestion d'une liste de collections
 */

namespace App\Admin\Collection;

use App\Admin\{ Collection as CollectionEditor };
use App\Collection\CollectionEditorCollection as Collection;

class CollectionEditorCollection extends Collection {
	
	const ORDER_BY_NAME = 'name';
	
	/*****************************************/
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = CollectionEditor::class;
	
	/*****************************************/
	
}