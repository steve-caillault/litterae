<?php

/**
 * Gestion HTML d'une liste de personnes
 */

namespace App\Admin\HTML\Collection;

use Root\{ HTML };
/***/
use App\{ Model, ImageResource };
use App\HTML\Collection\CollectionHTML;
use App\Collection\{ Collection };
/***/
use App\Admin\Person;
use App\Admin\Collection\PersonCollection;

class PersonCollectionHTML extends CollectionHTML {
	
	/**
	 * Type de tri
	 * @var string
	 */
	protected ?string $_order_by = PersonCollection::ORDER_BY_NAME;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = PersonCollection::DIRECTION_ASC;
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : Collection
	{
		return PersonCollection::factory();
	}
	
	/*****************************************************************/
	
	/* METHODES DE RENDU */
	
	/**
	 * Formatage des données d'un modèle
	 * @param Model $model
	 * @param mixed Index dans le tableau de la liste
	 * @return array
	 */
	protected function _formatModelData(Model $model, $index) : array
	{
		$title = HTML::anchor($model->adminEditUri(), $model->fullName(), [
			'title' => strtr('Modifier :name.', [ ':name' => $model->fullName(), ]),
		]);
		
		$birthCountry = $model->birthCountry();
		$countryFlag = $birthCountry->image('image', ImageResource::VERSION_SMALL, [ // @todo Optimiser pour ne pas faire de requête pour chaque objet
			'width' => 35,
			'height' => 25,
			'alt' => strtr('Drapeau du pays :name.', [
				':name' => $birthCountry->name,
			]),
		]);
		
		return [
			'attributes' => HTML::attributes([ 'class' => 'line', ]),
			'title' => $title,
			'country' => $countryFlag,
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		$anchor = HTML::anchor(Person::adminAddUri(), 'ici', [
			'title' => 'Ajouter une personne en cliquant ici.',
		]);
		
		return strtr('Aucune personne n\'a été trouvé. Cliquez :anchor pour en ajouter un.', [
			':anchor' => $anchor,
		]);
	}
	
	/**
	 * Retourne les clés des champs à afficher
	 * @return array
	 */
	protected function _fields() : array
	{
		return [ 'title', 'country', ];
	}
	
	/**
	 * Retourne les attributs des champs
	 * @return array
	 */
	protected function _fieldsAttributes() : array
	{
		return array_merge(parent::_fieldsAttributes(), [
			'country' => [ 'class' => 'with-image', ],
		]);
	}
	
	/*****************************************************************/
	
}