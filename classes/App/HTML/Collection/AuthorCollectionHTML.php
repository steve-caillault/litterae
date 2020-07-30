<?php

/**
 * Gestion HTML d'une liste d'auteurs
 */

namespace App\HTML\Collection;

use App\Contributor;

class AuthorCollectionHTML extends ContributorCollectionHTML {

	/**
	 * Type de contributeur
	 * @var string
	 */
	protected string $_contributor_type = Contributor::TYPE_AUTHOR;
	
	/*****************************************************************/
	
}