<?php

/**
 * Gestion HTML d'une liste de contributeur
 */

namespace App\HTML\Collection;

use Root\HTML;
/***/
use App\{ Model, Reader };
use App\Collection\{ Collection, ContributorCollection };
use App\HTML\Book\ContributorHTML;

abstract class ContributorCollectionHTML extends CollectionHTML {
	
	/**
	 * Type de tri
	 * @var string
	 */
	protected ?string $_order_by = ContributorCollection::ORDER_BY_NAME;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = Collection::DIRECTION_ASC;
	
	/**
	 * Lecteur identifié
	 * @var Reader
	 */
	protected Reader $_reader;
	
	/**
	 * Type de contributeur
	 * @var string
	 */
	protected string $_contributor_type;
	
	/**
	 * Vrai s'il faut filtrer les contributeurs suivis
	 * @var bool
	 */
	protected bool $_followed = FALSE;
	
	/**
	 * Nom de la vue utilisée pour l'affichage
	 * @var string
	 */
	protected string $_view_name = 'items/contributors';
	
	/*****************************************************************/
	
	/**
	 * Constructeur
	 * @param array $params
	 */
	protected function __construct(array $params = [])
	{
		$reader = getArray($params, 'reader');
		if(! $reader instanceof Reader)
		{
			exception('Le lecteur est incorrect.');
		}
		$this->_reader = $reader;
		
		$this->_followed = (bool) getArray($params, 'followed', $this->_followed);
		
		parent::__construct($params);
	}
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return Collection
	 */
	protected function _initCollection() : Collection
	{
		$collection = ContributorCollection::factory()->type($this->_contributor_type);
		
		if($this->_followed)
		{
			$collection->followedBy($this->_reader);
		}
		
		return $collection;
	}
	
	/**
	 * Retourne les clés des champs à afficher
	 * @return array
	 */
	protected function _fields() : array
	{
		return [];
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
		$contributorHTML = ContributorHTML::factory([
			'reader' => $this->_reader,
			'person' => $model,
			'type' => $this->_contributor_type,
		]);
		
		$booksUri = $contributorHTML->booksUri();
		$contributorAnchor = HTML::anchor($booksUri, $model->fullName(), [
			'title' => strtr('Consulter la liste des livres de :name.', [
				':name' => $model->fullName(),
			]),
		]);
		
		return [
			'name' => $contributorAnchor,
			'followed' => $contributorHTML->followedButton(),
		];
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		return strtr('Aucun :type n\'a été trouvé.', [
			':type' => translate(strtolower($this->_contributor_type), [
				'count' => 0,
			]),
		]);
	}
	
}