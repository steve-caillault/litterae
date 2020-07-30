<?php

/**
 * Gestion des contributeurs d'un livre depuis le panneau d'administration
 */

namespace App\Admin\HTML\Collection;

use Root\{ HTML };
/***/
use App\Model;
use App\Collection\Collection;
use App\HTML\Collection\CollectionHTML;
use App\Admin\Collection\ContributorCollection;
use App\Admin\{ Contributor, Book };

class ContributorCollectionHTML extends CollectionHTML {
	
	/**
	 * Type de contributeur
	 * @var string
	 */
	private string $_type;
	
	/**
	 * Livre
	 * @var Book
	 */
	private Book $_book;
	
	/**
	 * Type de tri
	 * @var string
	 */
	protected ?string $_order_by = ContributorCollection::ORDER_BY_NAME;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = ContributorCollection::DIRECTION_ASC;
	
	/**********************************************************/
	
	/**
	 * Constructeur
	 * @param array $params
	 */
	protected function __construct(array $params = [])
	{
		// Récupération du type de contributeur
		$type = getArray($params, 'type');
		if(! in_array($type, Contributor::allowedTypes()))
		{
			exception('Type de contributeur inconnu.');
		}
		
		// Récupération du livre
		$book = getArray($params, 'book');
		if(! $book instanceof Book)
		{
			exception('Livre incorrect.');
		}
		
		$this->_type = $type;
		$this->_book = $book;
		
		parent::__construct($params);
	}
	
	/**********************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : Collection
	{
		return ContributorCollection::factory()->book($this->_book)->type($this->_type);
	}
	
	/**********************************************************/
	
	/**
	 * Formatage des données d'un modèle
	 * @param Model $model
	 * @param mixed Index dans le tableau de la liste
	 * @return array
	 */
	protected function _formatModelData(Model $model, $index) : array
	{	
		$personAnchor = HTML::anchor($model->adminEditUri(), $model->fullName(), [
			'title' => strtr('Modifier :name.', [
				':name' => $model->fullName(),
			]),
		]);
		
		$contributor = Contributor::factory([
			'type' => $this->_type,
		]);
		$contributor->person($model);
		$contributor->book($this->_book);
		
		$deleteAnchor = HTML::anchor($contributor->adminDeleteUri(), '&times;', [
			'title' => strtr('Supprimer :name.', [
				':name' => $model->fullName(),
			]),
			'data-url' => getURL($contributor->adminAjaxDeleteUri()),
		]);
		
		return [
			'attributes' => HTML::attributes([ 'class' => 'line', ]),
			'name' => $personAnchor,
			'delete' => $deleteAnchor,
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		return NULL;
	}
	
	/**
	 * Retourne les clés des champs à afficher
	 * @return array
	 */
	protected function _fields() : array
	{
		return [ 'name', 'delete', ];
	}
	
	/**
	 * Retourne les attributs des champs
	 * @return array
	 */
	protected function _fieldsAttributes() : array
	{
		return array_merge(parent::_fieldsAttributes(), [
			'delete' => [ 'class' => 'with-delete', ],
		]);
	}
	
	/**
	 * Retourne les propriétés HTML de la collection
	 * @return array
	 */
	protected function _htmlAttributes() : array
	{
		$attributes = parent::_htmlAttributes();
		
		$contributorClass = strtr('contributor-collection contributor-:type-collection', [
			':type' => strtolower($this->_type),
		]);
		
		$classes = getArray($attributes, 'class') . ' ' . $contributorClass;
		$attributes['class'] = trim($classes);
		
		return $attributes;
		
	}
	
	/**********************************************************/
	
}