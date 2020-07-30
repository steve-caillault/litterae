<?php

/**
 * Vérification que le pays n'existe pas déjà en base de données
 */

namespace App\Validation\Rules;

use Root\Validation\Rules\Rule;
/***/
use App\Country;

class CountryNotAlreadyExistsRule extends Rule {
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'Ce pays existe déjà.';
	
	/**
	 * Le pays édité
	 * @var Country
	 */
	protected ?Country $_country = NULL;
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	public function check() : bool
	{
		$code = $this->_getValue();
		$this->_country = $this->_getParameter('country');
		
		$country = Country::factory($code);
		
		if($country !== NULL)
		{
			// S'il s'agit d'une édition, on vérifit que les deux codes correspondent
			if($this->_country !== NULL)
			{
				return ($this->_country->code === $country->code);
			}
			// Sinon le pays existe déjà
			return FALSE;
		}
		
		// Sinon le pays n'a pas été trouvé
		return TRUE;
	}
	
}