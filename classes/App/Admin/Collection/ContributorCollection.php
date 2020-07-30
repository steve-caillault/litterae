<?php

/**
 * Gestion des auteurs d'un livre depuis le panneau d'administration
 */

namespace App\Admin\Collection;

use App\Collection\{ ContributorCollection as Collection };
use App\Admin\Person;

class ContributorCollection extends Collection {

	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Person::class;
	
}