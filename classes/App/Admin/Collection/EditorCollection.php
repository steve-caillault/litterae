<?php

/**
 * Liste des éditeurs
 */

namespace App\Admin\Collection;

use App\Collection\CollectionEditorCollection as Collection;
use App\Admin\Editor;

class EditorCollection extends Collection {
	
	const ORDER_BY_NAME = 'name';
	
	/*****************************************/
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Editor::class;
	
	/*****************************************/
	
}