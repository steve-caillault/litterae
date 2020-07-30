<?php

/**
 * Formulaire de création et d'édition d'un éditeur
 */

namespace App\Forms\Admin\Editors;

use Root\{ Validation };
/***/
use App\Forms\ProcessForm;
use App\Admin\Editor;

class CreateOrEditForm extends ProcessForm {
	
	public const DATA_NAME = 'name';
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'admin-editor-create-or-edit';
	
	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_NAME => self::FIELD_TEXT,
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_NAME => NULL,
	];
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [
		self::DATA_NAME => 'Nom de l\'éditeur',
	];
	
	/**
	 * Vrai si on doit afficher le titre du formulaire
	 * @var bool
	 */
	protected static bool $_with_title = FALSE;
	
	/***********************************************************/
	
	/**
	 * Editeur qui est édité
	 * @var Editor
	 */
	private ?Editor $_editor = NULL;
	
	/***********************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
		$this->_editor = getArray($params, 'editor', $this->_editor);
		if($this->_editor !== NULL AND ! ($this->_editor instanceof Editor))
		{
			exception('Editeur incorrect.');
		}
		
		$data = getArray($params, 'data');
		if($this->_editor !== NULL)
		{
			$params['data'] = array_merge($this->_data, $this->_editor->asArray(), $data);
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
		
		// Fusion des données avec celles de l'éditeur que l'on édite
		if($this->_editor !== NULL)
		{
			$data = array_merge($this->_editor->asArray(), $data);
		}
		
		$editor = Editor::factory($data);
		
		$success = $editor->save();
		
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
			return Editor::adminListUri();
		}
		return NULL;
	}
	
	/***********************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	public function title() : string
	{
		if($this->_editor !== NULL)
		{
			return strtr('Modification de l\'éditeur :name', [ ':name' => $this->_editor->name ]);
		}
		return 'Création d\'un éditeur';
	}
	
	/***********************************************************/
	
}