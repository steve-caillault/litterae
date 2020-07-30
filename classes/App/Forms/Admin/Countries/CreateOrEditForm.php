<?php

/**
 * Formulaire de création et d'édition d'un pays
 */

namespace App\Forms\Admin\Countries;

use Root\{ Validation };
/***/
use App\Forms\ProcessForm;
use App\Admin\Country;
use App\ImageResource;

class CreateOrEditForm extends ProcessForm {
	
	public const DATA_CODE = 'code';
	public const DATA_NAME = 'name';
	public const DATA_IMAGE = 'image';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'admin-country-create-or-edit';

	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_CODE => self::FIELD_TEXT,
		self::DATA_NAME => self::FIELD_TEXT,
		self::DATA_IMAGE => self::FIELD_FILE,
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_CODE => NULL,
		self::DATA_NAME => NULL,
		self::DATA_IMAGE => NULL,
	];
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [
		self::DATA_CODE => 'Code ISO',
		self::DATA_NAME => 'Nom du pays',
		self::DATA_IMAGE => 'Drapeau du pays',
	];
	
	/**
	 * Vrai si on doit uploader des fichiers
	 * @var bool
	 */
	protected static bool $_must_upload_files = TRUE;
	
	/**
	 * Vrai si on doit afficher le titre du formulaire
	 * @var bool
	 */
	protected static bool $_with_title = FALSE;
	
	/***********************************************************/
	
	/**
	 * Pays qui est édité
	 * @var Country
	 */
	private ?Country $_country = NULL;
	
	/***********************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
		$this->_country = getArray($params, 'country', $this->_country);
		if($this->_country !== NULL AND ! ($this->_country instanceof Country))
		{
			exception('Pays incorrect.');
		}
			
		$data = getArray($params, 'data');
		if($this->_country !== NULL)
		{
			$params['data'] = array_merge($this->_data, $this->_country->asArray(), $data);
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
			self::DATA_CODE => [
				array('required'),
				array('exact_length', array('length' => 2)),
				array('country_not_already_exists', array('country' => $this->_country)),
			],
			self::DATA_NAME => [
				array('required'),
				array('min_length', array('min' => 4)),
				array('max_length', array('max' => 100)),
			],
			self::DATA_IMAGE => [
				array('upload_valid'),
				array('upload_extensions', array('types' => [ 'jpg', 'png', ])),
				array('upload_size', array('size' => 1)),
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
		
		unset($data[self::DATA_IMAGE]);
		
		// Fusion des données avec celles du pays que l'on édite
		if($this->_country !== NULL)
		{
			$data = array_merge($this->_country->asArray(), $data);
		}
		
		$country = Country::factory($data);
		
		$success = $country->save();
		
		// Téléchargement de l'image
		$file = getArray($this->_data, static::DATA_IMAGE);
		$country->uploadImage(static::DATA_IMAGE, $file);
		
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
			return Country::adminListUri();
		}
		return NULL;
	}
	
	/***********************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne les prévisualisation des champs (utilisé pour les champs de fichier)
	 * @return array
	 */
	protected function _inputPreviews() : array
	{
		$countryImage = NULL;
		if($this->_country !== NULL)
		{
			$countryImage = $this->_country->image('image', ImageResource::VERSION_SMALL, [
				'alt' => strtr('Drapeau du pays :name.', [
					':name' => $this->_country->name,
				]),
			]);
		}
		
		return array_merge(parent::_inputPreviews(), [
			self::DATA_IMAGE => $countryImage,
		]);
	}
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		if($this->_country !== NULL)
		{
			return strtr('Modification du pays :name', [ ':name' => $this->_country->name ]);
		}
		return 'Création d\'un pays';
	}
	
	/***********************************************************/
	
}