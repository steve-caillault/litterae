<?php

/**
 * Formulaire de création et d'édition d'une collection
 */

namespace App\Forms\Admin\Collections;

use Root\{ Validation };
/***/
use App\Forms\ProcessForm;
use App\Admin\{ Collection, Editor };

class CreateOrEditForm extends ProcessForm {
	
	public const DATA_NAME = 'name';
	public const DATA_EDITOR = 'editor';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'admin-collection-create-or-edit';
	
	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_NAME => self::FIELD_TEXT,
		self::DATA_EDITOR => self::FIELD_AUTOCOMPLETE,
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_NAME => NULL,
		self::DATA_EDITOR => NULL,
	];
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [
		self::DATA_NAME => 'Nom de la collection',
		self::DATA_EDITOR => 'Editeur',
	];
	
	/**
	 * Vrai si on doit afficher le titre du formulaire
	 * @var bool
	 */
	protected static bool $_with_title = FALSE;
	
	/***********************************************************/
	
	/**
	 * Collection qui est éditée
	 * @var Collection
	 */
	private ?Collection $_collection = NULL;
	
	/***********************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
		$this->_collection = getArray($params, 'collection', $this->_collection);
		if($this->_collection !== NULL AND ! ($this->_collection instanceof Collection))
		{
			exception('Collection incorrecte.');
		}
		
		$data = getArray($params, 'data');
		if($this->_collection !== NULL)
		{
			$params['data'] = array_merge($this->_data, $this->_collection->asArray(), $data);
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
			self::DATA_NAME => [
				array('required'),
				array('min_length', array('min' => 5)),
				array('max_length', array('max' => 100)),
			],
			self::DATA_EDITOR => [
				array('required'),
				array('model_exists', [
					'class' => Editor::class,
					'criterias' => [
						'id' => getArray($this->_data, self::DATA_EDITOR),
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
		
		// Fusion des données avec celles de la collection que l'on édite
		if($this->_collection !== NULL)
		{
			$data = array_merge($this->_collection->asArray(), $data);
		}
		
		$collection = Collection::factory($data);
		
		$success = $collection->save();
		
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
			return Collection::adminListUri();
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
		if($name == self::DATA_EDITOR)
		{
			$editorId = getArray($this->_data, self::DATA_EDITOR);
			return array_merge(Editor::adminSearchField($editorId), [
				'text_id' => self::DATA_EDITOR,
			]);
		}
		return parent::_autocompleteOptions($name);
	}
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		if($this->_collection !== NULL)
		{
			return strtr('Modification de la collection :name', [ ':name' => $this->_collection->name ]);
		}
		return 'Création d\'une collection';
	}
	
	/***********************************************************/
	
}