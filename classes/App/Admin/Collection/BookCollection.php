<?php

/**
 * Liste des livres
 */

namespace App\Admin\Collection;

use App\Collection\BookCollection as Collection;
use App\Admin\Book;

class BookCollection extends Collection {
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Book::class;
	
	/*****************************************/
	
}