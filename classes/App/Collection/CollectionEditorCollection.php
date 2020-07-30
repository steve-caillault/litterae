<?php

/**
 * Gestion d'une liste de collections
 */

namespace App\Collection;

use App\{ Collection as CollectionEditor };

class CollectionEditorCollection extends Collection {
	
	const ORDER_BY_NAME = 'name';
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = CollectionEditor::class;
	
	/***************************************************************/
	
	/* FILTRES */
	
	/**
	 * Recherche
	 * @param string $search
	 * @return self
	 */
	public function search(string $search) : self
	{
		$this->_query->where($this->_table . '.name', 'LIKE', '%' . $search . '%');
		return $this;
	}
	
	/***************************************************************/
	
	/* TRIS */
	
	/**
	 * Tri par nom
	 * @param string $direction
	 * @return self
	 */
	protected function _orderByName(string $direction = self::DIRECTION_ASC) : self
	{
		$this->_query->orderBy($this->_table . '.name', $direction);
		return $this;
	}
	
	/***************************************************************/
	
}