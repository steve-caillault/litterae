<?php

/**
 * Gestion d'une personne contribuant à l'écriture d'un livre (auteur ou traducteur)
 */

namespace App;

abstract class Contributor extends BaseContributor {
	
	/**
	 * Méthode de construction statique à partir des paramètres nécessaire pour l'instanciation
	 * @param array $params Paramètre de l'objet
	 * @return self
	 */
	public static function _construct(array $params) : Model
	{
		$type = getArray($params, 'type');
		$class = __NAMESPACE__ . '\\' . ucfirst(strtolower($type)) . 'Contributor';
		return new $class($params);
	}
	
	/**
	 * Retourne l'URI de la liste des livres du contributeur
	 * @return string
	 */
	abstract public function booksUri() : string;
}