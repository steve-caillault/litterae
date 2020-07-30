<?php

/**
 * Gestion d'une liste de personnes
 */

namespace App\Collection;

use App\{ Person };

class PersonCollection extends Collection {
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Person::class;
	
	/***************************************************************/
	
}