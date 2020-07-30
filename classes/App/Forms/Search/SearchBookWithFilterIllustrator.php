<?php

/**
 * Formulaire de recherche de livre avec filtre d'illustrateur
 */

namespace App\Forms\Search;

use Root\{ Route };
/***/
use App\{ Person };

class SearchBookWithFilterIllustrator extends SearchBookForm {
	
	/**
	 * Illustrateur dont les livres sont filtrés
	 * @var Person
	 */
	private Person $_illustrator;
	
	/****************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION  */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
	    $illustrator = getArray($params, 'illustrator');
	    if($illustrator === NULL OR ! $illustrator instanceof Person)
		{
			exception('Illustrateur incorrect.');
		}
		$this->_illustrator = $illustrator;
		parent::__construct($params);
	}
	
	/****************************************************************************/
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Route::retrieve('illustrators.item')->uri([
			'illustratorId' => $this->_illustrator->id,
		]));
	}
	
	/****************************************************************************/
	
}