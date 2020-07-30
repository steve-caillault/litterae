<?php

/**
 * Trait gérant une collection de personnes
 */

namespace App\Traits\Collection;

use Root\DB;

trait WithPerson {
	
	/* FILTRES */
	
	/**
	 * Recherche
	 * @param string $search
	 * @return self
	 */
	public function search(string $search) : self
	{
		$fields = [
			$this->_table . '.first_name',
			$this->_table . '.second_name',
			$this->_table . '.last_name',
		];
		
		$this->_query->whereExpression(function($builder) use ($fields, $search) {
			foreach($fields as $field)
			{
				$builder->orWhere($field, 'LIKE', '%' . $search . '%');
			}
			$fieldFullName = DB::expression(strtr('CONCAT_WS(\' \', :fields)', [
				':fields' => implode(', ', $fields),
			]));
			$builder->orWhere($fieldFullName, 'LIKE', '%' . $search . '%');
		});
			
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
		$fields = [
			$this->_table . '.first_name',
			$this->_table . '.second_name',
			$this->_table . '.last_name',
		];
		
		$field = DB::expression(strtr('CONCAT_WS(\' \', :fields)', [
			':fields' => implode(', ', $fields),
		]));
		$this->_query->orderBy($field, $direction);
		return $this;
	}
	
	/*****************************************/
	
}