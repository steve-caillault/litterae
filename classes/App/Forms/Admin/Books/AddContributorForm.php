<?php

/**
 * Formulaire d'ajout d'un contributeur à un livre
 */

namespace App\Forms\Admin\Books;

use Root\{ Validation };
/***/
use App\Forms\ProcessForm;
use App\Admin\{ Book, Contributor, Person };

abstract class AddContributorForm extends ProcessForm {
	
	public const DATA_PERSON = 'person';
	
	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_PERSON => self::FIELD_AUTOCOMPLETE,
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_PERSON => NULL,
	];
	
	/**
	 * Vrai si on doit afficher le titre du formulaire
	 * @var bool
	 */
	protected static bool $_with_title = FALSE;
	
	/**
	 * Texte du bouton de soumission du formulaire
	 * @var string
	 */
	protected static string $_submit_label = 'Ajouter';
	
	/**
	 * Vrai si le formulaire doit être affiché sur une ligne
	 * @var bool
	 */
	protected static bool $_render_inline = TRUE;
	
	/***********************************************************/
	
	/**
	 * Livre qui est édité
	 * @var Book
	 */
	private Book $_book;
	
	/**
	 * Type de contributeur
	 * @var string
	 */
	private string $_contributor_type;
	
	/**
	 * Contributeur qui a été ajoute
	 * @var Contributor
	 */
	private ?Contributor $_contributor = NULL;
	
	/***********************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Instanciation
	 * @return self
	 */
	public static function factory($params = NULL) : self
	{
		$allowedTypes = Contributor::allowedTypes();
		$type = getArray($params, 'contributor_type');
		if(! in_array($type, $allowedTypes))
		{
			exception('Type de contributeur incorrect.');
		}
	
		$class = __NAMESPACE__ . '\Add' . ucfirst(strtolower($type)) . 'Form';
		return new $class($params);
	}
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 */
	protected function __construct(array $params)
	{
		$book = getArray($params, 'book');
		if(! $book instanceof Book)
		{
			exception('Livre incorrect.');
		}
		
		$this->_book = $book;
		$this->_contributor_type = getArray($params, 'contributor_type');
		parent::__construct($params);
	}
	
	/***********************************************************/
	
	/* VALIDATION */
	
	/**
	 * Retourne l'objet Validation initialisé avec les réglés de validation
	 * @return Validation
	 */
	protected function _initValidation() : Validation
	{
		$rules = [
			static::DATA_PERSON => [
				array('required'),
				// Vérifit que la personne existe
				array('model_exists', [
					'class' => Person::class,
					'criterias' => [
						'id' => getArray($this->_data, static::DATA_PERSON),
					],
				]),
				// Vérifit que le contributeur n'existe pas déjà
				array('model_not_exists', [
					'class' => Contributor::class,
					'criterias' => [
						'book' => $this->_book->id,
						'person' => getArray($this->_data, static::DATA_PERSON),
						'type' => $this->_contributor_type,
					],
				]),
			],
		];
		
		$validation = Validation::factory([
			'data' 	=> $this->_data,
			'rules'	=> $rules,
		]);
		
		return $validation;
	}
	
	/***********************************************************/
	
	/* TRAITEMENT DU FORMULAIRE */
	
	/**
	 * Méthode à exécuter si le formulaire est valide
	 * @return bool
	 */
	protected function _onValid() : bool
	{
		$contributor = Contributor::factory([
			'person' => getArray($this->_data, static::DATA_PERSON),
			'book' => $this->_book->id,
			'type' => $this->_contributor_type,
		]);
		
		$success = $contributor->save();
		
		if($success)
		{
			$this->_contributor = $contributor;
		}
		
		return $success;
	}
	
	/**
	 * Retourne l'URL de redirection où rediriger en cas de succès
	 * @return string
	 */
	public function redirectUrl() : ?string
	{
		if($this->success())
		{
			return Book::adminEditUri();
		}
		return NULL;
	}
	
	/**
	 * Retourne la réponse du traitement du formulaire (utilisé lors d'un appel Ajax notamment)
	 * @return array
	 *		'success': <boolean>,
	 *		'errors': <array>
	 */
	public function response() : array
	{
		$response = parent::response();
		
		if($this->success())
		{
			$person = $this->_contributor->person();
			$response['contributor'] = [
				'name' => $person->fullName(),
				'editURL' => getURL($person->adminEditUri()),
				'deleteURL' => getURL($this->_contributor->adminDeleteUri()),
				'ajaxDeleteURL' => getURL($this->_contributor->adminAjaxDeleteUri()),
			];
		}
		
		return $response;
	}
	
	/***********************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne les attributs du formulaire
	 * @return array
	 */
	protected function _attributes() : array
	{
		$attributes = parent::_attributes();
		
		$addUri = $this->_book->adminAddContributorUri($this->_contributor_type);
		
		$attributes['class'] .= ' add-contributor-form';
		$attributes['data-contributor-type'] = strtolower($this->_contributor_type);
		$attributes['data-input-name'] = static::DATA_PERSON;
		$attributes['data-add-contributor-url'] = getURL($addUri);
		
		return $attributes;
	}
	
	/**
	 * Retourne les options du champs d'autocomplètion dont le nom du champs est en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _autocompleteOptions(string $name) : array
	{
		if($name == static::DATA_PERSON)
		{
			$personId = getArray($this->_data, static::DATA_PERSON);
			$attributes = array_merge(Person::adminSearchField($personId), [
				'text_id' => static::DATA_PERSON,
			]);
		
			return $attributes;
		}
		return parent::_autocompleteOptions($name);
	}
	
	/***********************************************************/
	
}