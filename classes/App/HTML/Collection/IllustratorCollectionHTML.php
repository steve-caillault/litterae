<?php

/**
 * Gestion HTML d'une liste d'illustrateurs
 */

namespace App\HTML\Collection;

use App\Contributor;

class IllustratorCollectionHTML extends ContributorCollectionHTML {
	
	/**
	 * Type de contributeur
	 * @var string
	 */
	protected string $_contributor_type = Contributor::TYPE_ILLUSTRATOR;
	
	/*****************************************************************/
	
}