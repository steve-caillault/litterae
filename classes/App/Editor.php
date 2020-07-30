<?php

/**
 * Gestion d'un éditeur de livre
 */

namespace App;

class Editor extends Model {
	
	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'editors';
	
	/*********************************************************************/
	
	/* CHAMPS EN BASE DE DONNEES */
	
	/**
	 * Identifiant de l'éditeur en base de données
	 * @var int
	 */
	public ?int $id = NULL;
	
	/**
	 * Nom de l'éditeur
	 * @var string
	 */
	public ?string $name = NULL;
	
	/*********************************************************************/
	
}