<?php

/**
 * Classes pour les objets du modèle utilisant un drapeau
 */

namespace App\Traits\Models;

trait WithFlag {
	
	/**
	 * Code à deux lettres permettant d'identifier le drapeau
	 * @var string
	 */
	public ?string $code = NULL;
	
	/**
	 * Nom du drapeau
	 * @var string
	 */
	public ?string $name = NULL;
	
	/**
	 * Référence de l'image
	 * @var int
	 */
	public ?int $image = NULL;
	
}