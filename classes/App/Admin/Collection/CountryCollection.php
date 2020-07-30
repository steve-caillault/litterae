<?php

/**
 * Liste des pays
 */

namespace App\Admin\Collection;

use App\Collection\Collection;
use App\Admin\Country;

class CountryCollection extends Collection {
	
	public const ORDER_BY_CODE = 'code';
	public const ORDER_BY_NAME = 'name';
	
	/*****************************************/
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = Country::class;
	
	/*****************************************/
	
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
	
	/*****************************************/
	
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
	
	/**
	 * Tri par code ISO
	 * @param string $direction
	 * @return self
	 */
	protected function _orderByCode(string $direction = self::DIRECTION_ASC) : self
	{
		$this->_query->orderBy($this->_table . '.code', $direction);
		return $this;
	}
	
	/*****************************************/
	
}