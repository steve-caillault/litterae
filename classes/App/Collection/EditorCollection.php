<?php

/**
 * Gestion d'une liste d'éditeurs
 */

namespace App\Collection;

use App\{ Editor };

class EditorCollection extends CollectionEditorCollection {
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Editor::class;
	
	/***************************************************************/
	
}