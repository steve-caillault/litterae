<?php

/**
 * Formulaire de recherche de livre avec filtre de traducteur
 */

namespace App\Forms\Search;

use Root\{ Route };
/***/
use App\{ Person };

class SearchBookWithFilterTranslator extends SearchBookForm {
	
	/**
	 * Traducteur dont les livres sont filtrés
	 * @var Person
	 */
	private Person $_translator;
	
	/****************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION  */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
	    $translator = getArray($params, 'translator');
	    if($translator === NULL OR ! $translator instanceof Person)
		{
			exception('Traducteur incorrect.');
		}
		$this->_translator = $translator;
		parent::__construct($params);
	}
	
	/****************************************************************************/
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Route::retrieve('translators.item')->uri([
			'translatorId' => $this->_translator->id,
		]));
	}
	
	/****************************************************************************/
	
}