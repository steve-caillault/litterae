<?php

/**
 * Personne suivi par un lecteur
 */

namespace App;

use App\Traits\Models\{ WithReader };

class PersonFollowed extends WithPerson {
	
	use WithReader;
	
	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'persons_followed';
	
	/**
	 * Clé primaire
	 * @var string
	 */
	protected static string $_primary_key = 'reader|person|type';
	
	/**
	 * Vrai si la clé primaire est un auto-incrément
	 * @var bool
	 */
	protected static bool $_autoincrement = FALSE;
	
}