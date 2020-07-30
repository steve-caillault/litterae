<?php

/**
 * Gestion d'une liste de personnes à suivre
 */

namespace App\Collection;

use App\{ Reader, PersonFollowed };

class PersonFollowedCollection extends Collection {
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = PersonFollowed::class;
	
	/********************************************************/
	
	/**
	 * Filtre les personnes suivient par le lecteur en paramètre
	 * @param Reader $reader
	 * @return self
	 */
	public function reader(Reader $reader) : self
	{
		$this->_query->where($this->_table . '.reader', '=', $reader->id);
		return $this;
	}
	
	/**
	 * Filtre le type de personne en paramètre
	 * @param string $type
	 * @return self
	 */
	public function type(string $type) : self
	{
		$this->_query->where($this->_table . '.type', '=', $type);
		return $this;
	}
	
	/********************************************************/
	
}