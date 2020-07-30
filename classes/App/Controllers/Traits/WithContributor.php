<?php

/**
 * Trait utilisé pour les contrôleurs utilisant un contributeur d'un livre
 */

namespace App\Controllers\Traits;

use App\{
	Site,
	BaseContributor,
	Contributor,
	Admin\Contributor as ContributorAdmin
};

trait WithContributor {
	
	use WithBook, WithPerson;
	
	/**
	 * Contributeur à gérer
	 * @var BaseContributor
	 */
	protected ?BaseContributor $_contributor = NULL;
	
	/**
	 * Affecte le livre de la requête HTTP
	 * @return void
	 */
	protected function _retrieveContributor() : void
	{
		$contributorTypeParam = $this->request()->parameter('contributorType');
		
		$this->_retrieveBook();
		$this->_retrievePerson();
		
		// Pas de contributeur à charger
		if($contributorTypeParam === NULL OR $this->_book === NULL OR $this->_person === NULL)
		{
			return;
		}
		
		$siteType = Site::type();
		$class = ($siteType == Site::TYPE_ADMIN) ? ContributorAdmin::class : Contributor::class;
		
		// Vérification du type
		$contributorType = strtoupper($contributorTypeParam);
		if(! in_array($contributorType, $class::allowedTypes()))
		{
			exception('Type de contributeur incorrect.');
		}
		
		// Chargement du contributeur
		$primaryKey = implode('|', [
			$this->_book->id, $this->_person->id, $contributorType,
		]);
		$this->_contributor = $class::factory($primaryKey);
		
		if($this->_contributor === NULL)
		{
			exception('Contributeur inconnu', 404);
		}
		
		// Affecte le livre et la personne au contributeur pour optimiser
		$this->_contributor->book($this->_book);
		$this->_contributor->person($this->_person);
	}
	
}