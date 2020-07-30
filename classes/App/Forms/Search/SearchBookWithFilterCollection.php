<?php

/**
 * Formulaire de recherche de livre avec filtre de collection
 */

namespace App\Forms\Search;

use Root\{ Route };
/***/
use App\{ Collection };

class SearchBookWithFilterCollection extends SearchBookForm {
	
	/**
	 * Collection dont les livres sont filtrés
	 * @var Collection
	 */
    private Collection $_collection;
	
	/****************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION  */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
		$collection = getArray($params, 'collection');
		if($collection === NULL OR ! $collection instanceof Collection)
		{
			exception('Collection incorrecte.');
		}
		$this->_collection = $collection;
		parent::__construct($params);
	}
	
	/****************************************************************************/
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return getURL(Route::retrieve('collections.item')->uri([
			'collectionId' => $this->_collection->id,
		]));
	}
	
	/****************************************************************************/
	
}