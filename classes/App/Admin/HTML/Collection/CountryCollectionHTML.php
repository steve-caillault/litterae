<?php

/**
 * Gestion HTML d'une liste de pays
 */

namespace App\Admin\HTML\Collection;

use Root\{ HTML };
/***/
use App\{ Model, ImageResource };
use App\HTML\Collection\{
	CollectionHTML,
	Traits\WithImagesLoading
};
use App\Collection\{ Collection };
/***/
use App\Admin\Country;
use App\Admin\Collection\CountryCollection;

class CountryCollectionHTML extends CollectionHTML {
	
	use WithImagesLoading;
	
	/**
	 * Type de tri
	 * @var string
	 */
	protected ?string $_order_by = CountryCollection::ORDER_BY_CODE;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = CountryCollection::DIRECTION_ASC;
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : Collection
	{
		return CountryCollection::factory();
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
		$name = HTML::anchor($model->adminEditUri(), $model->name, [
			'title' => strtr('Modifier le pays :name.', [ ':name' => $model->name, ]),
		]);
		
		$imageResources = getArray($this->_images($model), 'image');
		$imageResource = getArray($imageResources, ImageResource::VERSION_SMALL);
		
		$image = NULL;
		if($imageResource !== NULL)
		{
			$imageURL = $imageResource->url();
			$image = HTML::image($imageURL, [
				'width' => 35,
				'height' => 25,
				'alt' => strtr('Drapeau du pays :name.', [
					':name' => $model->name,
				])
			]);
		}
		
		return [
			'attributes' => HTML::attributes([ 'class' => 'line', ]),
			'code' => $model->code,
			'name' => $name,
			'image' => $image,
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		$anchor = HTML::anchor(Country::adminAddUri(), 'ici', [
			'title' => 'Ajouter un pays en cliquant ici.',
		]);
		
		return strtr('Aucun pays n\'a été trouvé. Cliquez :anchor pour en ajouter un.', [
			':anchor' => $anchor,
		]);
	}
	
	/**
	 * Retourne les clés des champs à afficher
	 * @return array
	 */
	protected function _fields() : array
	{
		return [ 'code', 'image', 'name', ];
	}
	
	/**
	 * Retourne les attributs des champs
	 * @return array
	 */
	protected function _fieldsAttributes() : array
	{
		return array_merge(parent::_fieldsAttributes(), [
			'image' => [ 'class' => 'with-image', ],
		]);
	}
	
	/*****************************************************************/
	
}