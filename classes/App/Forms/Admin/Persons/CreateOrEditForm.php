<?php

/**
 * Formulaire de création et d'édition d'une personne
 */

namespace App\Forms\Admin\Persons;

use Root\{ Validation };
/***/
use App\Forms\ProcessForm;
use App\Admin\{ Person, Country };

class CreateOrEditForm extends ProcessForm {
	
	public const DATA_FIRST_NAME = 'first_name';
	public const DATA_SECOND_NAME = 'second_name';
	public const DATA_LAST_NAME = 'last_name';
	public const DATA_BIRTHDATE = 'birthdate';
	public const DATA_BIRTH_COUNTRY = 'birth_country';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'admin-person-create-or-edit';
	
	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_FIRST_NAME => self::FIELD_TEXT,
		self::DATA_SECOND_NAME => self::FIELD_TEXT,
		self::DATA_LAST_NAME => self::FIELD_TEXT,
		self::DATA_BIRTHDATE => self::FIELD_DATE,
		self::DATA_BIRTH_COUNTRY => self::FIELD_AUTOCOMPLETE,
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_FIRST_NAME => NULL,
		self::DATA_SECOND_NAME => NULL,
		self::DATA_LAST_NAME => NULL,
		self::DATA_BIRTHDATE => NULL,
		self::DATA_BIRTH_COUNTRY => NULL,
	];
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [
		self::DATA_FIRST_NAME => 'Prénom',
		self::DATA_SECOND_NAME => 'Deuxième nom',
		self::DATA_LAST_NAME => 'Nom',
		self::DATA_BIRTHDATE => 'Date de naissance',
		self::DATA_BIRTH_COUNTRY => 'Pays de naissance',
	];
	
	/**
	 * Vrai si on doit afficher le titre du formulaire
	 * @var bool
	 */
	protected static bool $_with_title = FALSE;
	
	/***********************************************************/
	
	/**
	 * Personne qui est éditée
	 * @var Person
	 */
	private ?Person $_person = NULL;
	
	/***********************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
		$this->_person = getArray($params, 'person', $this->_person);
		if($this->_person !== NULL AND ! ($this->_person instanceof Person))
		{
			exception('Personne incorrecte.');
		}
		
		$data = getArray($params, 'data');
		if($this->_person !== NULL)
		{
			$params['data'] = array_merge($this->_data, $this->_person->asArray(), $data);
		}
		
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
			self::DATA_FIRST_NAME => [
				array('required'),
				array('min_length', array('min' => 3)),
				array('max_length', array('max' => 100)),
			],
			self::DATA_SECOND_NAME => [
				array('min_length', array('min' => 3)),
				array('max_length', array('max' => 100)),
			],
			self::DATA_LAST_NAME => [
				array('required'),
				array('min_length', array('min' => 3)),
				array('max_length', array('max' => 100)),
			],
			self::DATA_BIRTHDATE => [
				array('date', array('format' => 'Y-m-d')),
			],
			self::DATA_BIRTH_COUNTRY => [
				array('required'),
				array('model_exists', [
					'class' => Country::class,
					'criterias' => [
						'code' => getArray($this->_data, self::DATA_BIRTH_COUNTRY),
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
		$data = $this->_data;
		
		// Fusion des données avec celles de la personne que l'on édite
		if($this->_person !== NULL)
		{
			$data = array_merge($this->_person->asArray(), $data);
		}
		
		$person = Person::factory($data);
		
		$success = $person->save();
		
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
			return Person::adminListUri();
		}
		return NULL;
	}
	
	/***********************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne les options du champs d'autocomplètion dont le nom du champs est en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _autocompleteOptions(string $name) : array
	{
		if($name == self::DATA_BIRTH_COUNTRY)
		{
			$countryCode = getArray($this->_data, self::DATA_BIRTH_COUNTRY);
			return array_merge(Country::adminSearchField($countryCode), [
				'text_id' => self::DATA_BIRTH_COUNTRY,
			]);
		}
		return parent::_autocompleteOptions($name);
	}
	
	/**
	 * Retourne les paramètres du labels dont la clé est en paramètre
	 * @param string $key
	 * @return array
	 */
	protected function _labelsAttributes(string $key) : array
	{
		$attributes = parent::_labelsAttributes($key);
		
		return $attributes;
	}
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		if($this->_person !== NULL)
		{
			return strtr('Modification de :name', [ ':name' => $this->_person->fullName() ]);
		}
		return 'Création d\'une personne';
	}
	
	/***********************************************************/
	
}