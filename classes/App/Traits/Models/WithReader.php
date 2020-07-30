<?php

/**
 * Classes pour les objets du modèle utilisant un lecteur
 */

namespace App\Traits\Models;

use App\Reader;

trait WithReader {
	
	/**
	 * Identifiant du lecteur en base de données
	 * @var string
	 */
	public ?string $reader = NULL;
	
	/**
	 * Lecteur
	 * @var Reader
	 */
	private ?Reader $_reader = NULL;
	
	/**************************************************************/
	
	/**
	 * Retourne la personne
	 * @param Reader $reader Si renseigné, le lecteur à affecter
	 * @return Reader
	 */
	public function reader(?Reader $reader = NULL) : Reader
	{
		if($reader !== NULL)
		{
			$this->_reader = $reader;
			$this->reader = $reader->id;
		}
		elseif($this->_reader === NULL AND $this->reader !== NULL)
		{
			$this->_reader = Reader::factory($this->reader);
		}
		return $this->_reader;
	}
	
	/**************************************************************/
	
}