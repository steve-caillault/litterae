<?php

/**
 * Gestion du HTML d'un contributeur
 */

namespace App\HTML\Book;

use Root\{ Instanciable, HTML };
/***/
use App\{ Person, Reader };

abstract class ContributorHTML extends Instanciable {
	
	/**
	 * Lecteur connecté
	 * @var Reader
	 */
	private Reader $_reader;
	
	/**
	 * Personne représentant le contributeur
	 * @var Person
	 */
	protected Person $_person;
	
	/**
	 * Type de contributeur
	 * @var string
	 */
	private string $_type;
	
	/**
	 * Vrai si on affiche un bouton pour l'abonnement
	 * @var bool
	 */
	protected bool $_with_subscription_button = FALSE;
	
	/**************************************************/
	
	/**
	 * Instanciation
	 * @return self
	 */
	public static function factory($params = NULL) : self
	{
		$type = getArray($params, 'type');
		$class = __NAMESPACE__ . '\\' . ucfirst(strtolower($type)) . 'ContributorHTML';
		return new $class($params);
	}
	
	/**
	 * Constructeur 
	 * @param array $params
	 */
	private function __construct(array $params)
	{
		$this->_reader = getArray($params, 'reader');
		$this->_person = getArray($params, 'person');
		$this->_type = getArray($params, 'type');
	}
	
	/**************************************************/
	
	/**
	 * Retourne l'URI de la liste des livres du contributeur
	 * @return string
	 */
	abstract public function booksUri() : string;
	
	/**
	 * Retourne le bouton pour suivre un contributeur
	 * @return string
	 */
	public function followedButton() : ?string
	{
		if(! $this->_with_subscription_button)
		{
			return NULL;
		}
		
		$followed = $this->_reader->followed($this->_person, $this->_type);
		
		$descriptionParams = [
			':person' => $this->_person->fullName(),
			':type' => translate(strtolower($this->_type), [
				'count' => 2,
			]),
		];
	
		$addDescription = strtr('Ajouter :person à la liste des :type suivis.', $descriptionParams);
		$deleteDescription = strtr('Supprimer :person de la liste des :type suivis.', $descriptionParams);
		$description = ($followed) ? $deleteDescription : $addDescription;
		
		$classes = [ 'manage-list', ];
		if($followed)
		{
			$classes[] = 'selected';
		}
		
		return HTML::tag('button', [
			'type' => 'button',
			'class' => implode(' ', $classes),
			'content' => translate('followed', [ 'count' => 0, ]),
			'title' => $description,
			'data-type' => ($followed) ? 'delete' : 'add',
			/***/
			'data-url-delete' => getURL($this->_person->unfollowUri($this->_type)),
			'data-url-add' => getURL($this->_person->followUri($this->_type)),
			/***/
			'data-description-add' => $addDescription,
			'data-description-delete' => $deleteDescription,
		]);
	}
	
	/**************************************************/
	
}