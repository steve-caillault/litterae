<?php

/**
 * Gestion HTML d'une liste de traducteurs
 */

namespace App\HTML\Collection;

use App\Contributor;

class TranslatorCollectionHTML extends ContributorCollectionHTML {
	
	/**
	 * Type de contributeur
	 * @var string
	 */
	protected string $_contributor_type = Contributor::TYPE_TRANSLATOR;
	
	/*****************************************************************/
	
}