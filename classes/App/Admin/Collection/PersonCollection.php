<?php

/**
 * Liste des personnes
 */

namespace App\Admin\Collection;

use App\Collection\PersonCollection as Collection;
use App\Admin\Person;
use App\Traits\Collection\WithPerson;

class PersonCollection extends Collection {
	
	use WithPerson;
	
	const ORDER_BY_NAME = 'name';
	
	/*****************************************/
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Person::class;
	
	/*****************************************/
	
}