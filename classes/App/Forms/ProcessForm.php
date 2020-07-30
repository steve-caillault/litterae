<?php

/**
 * Gestion du traitement et du rendu d'un formulaire
 * @author Stève Caillault
 */

namespace App\Forms;

use Root\{ Validation, View, HTML, Instanciable };
/***/
use App\HTML\FormHTML;

abstract class ProcessForm extends Instanciable
{
	protected const METHOD_GET 		= 'GET';
	protected const METHOD_POST 	= 'POST';
	/***/
	protected const FIELD_TEXT 		= 'text';
	protected const FIELD_NUMBER	= 'number';
	protected const FIELD_PASSWORD	= 'password';
	protected const FIELD_DATE		= 'date';
	protected const FIELD_TEXTAREA 	= 'textarea';
	protected const FIELD_SELECT 	= 'select';
	protected const FIELD_FILE 		= 'file';
	protected const FIELD_HIDDEN	= 'hidden';
	protected const FIELD_AUTOCOMPLETE = 'autocomplete';
	/***/
	protected const DATA_FORM_NAME 	= 'form_name';
	protected const DATA_SUBMIT = 'submit';
	
	/**********************************************************************************************************/
	
	/**
	 * Nom du formulaire
	 * @var string 
	 */
	public static string $name;
	
	/**
	 * Noms de champs autorisés 
	 * @var array
	 */
	protected static array $_allowed_names = [];
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [];
	
	/**
	 * Liste des textes des champs lorsqu'ils sont vides
	 * @var array
	 */
	protected static array $_placeholders = [];
	
	/**********************************************************************************************************/
	
	/**
	 * Données du formulaire
	 * @var array 
	 */
	protected array $_data = [];
	
	/**
	 * Objet validation 
	 * @var Validation
	 */
	protected ?Validation $_validation = NULL;
	
	
	/**
	 * Vrai si le formulaire a pu être traité avec succès (reste à NULL si le formulaire n'a pas été posté)
	 * @var bool 
	 */
	private ?bool $_success = NULL;
	
	/**
	 * Erreurs de validation du formulaire
	 * @var array 
	 */
	protected array $_errors = [];
	
	/**********************************************************************************************************/
	
	/**
	 * Chemin de la vue à utiliser pour le rendue
	 * @var string
	 */
	protected static ?string $_file_view = NULL;
	
	/**
	 * Méthode de transmission des données
	 * @var string
	 */
	protected static string $_method = self::METHOD_POST;
	
	/**
	 * Vrai si on doit uploader des fichiers
	 * @var bool
	 */
	protected static bool $_must_upload_files = FALSE;
	
	/**
	 * Vrai si on doit afficher le titre du formulaire
	 * @var bool
	 */
	protected static bool $_with_title = TRUE;
	
	/**
	 * Texte du bouton de soumission du formulaire
	 * @var string
	 */
	protected static string $_submit_label = 'Envoyer';
	
	/**
	 * Vrai si le formulaire doit être affiché sur une ligne
	 * @var bool
	 */
	protected static bool $_render_inline = FALSE;
	
	/**
	 * Vrai si la position des labels et des champs doivent-être inversés
	 * @var bool 
	 */
	protected static bool $_reverse_labels_inputs_position = FALSE;
	
	/**********************************************************************************************************/
	
	/* VALIDATION */
	
	/**
	 * Test si le nom en paramètre est un nom de champs valide
	 * @return bool
	 */
	private static function _allowedName($name) : bool
	{
		if($name == static::DATA_FORM_NAME)
		{
			return TRUE;
		}
		return array_key_exists($name, static::$_allowed_names);
	}
	
	/**
	 * Retourne l'objet validation
	 * @return Validation 
	 */
	protected function _validation() : Validation
	{
		if($this->_validation === NULL)
		{
			$this->_validation = $this->_initValidation();
		}
		return $this->_validation;
	}
	
	/**
	 * Retourne l'objet Validation initialisé avec les réglés de validation
	 * @return Validation
	 */
	abstract protected function _initValidation() : Validation;
	
	/**********************************************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur 
	 * @param array $params Paramètres
	 */
	protected function __construct(array $params)
	{
		$this->_data(getArray($params, 'data', $this->_data));
	}
	
	/**********************************************************************************************************/

	/* TRAITEMENT DU FORMULAIRE */
	
	/**
	 * Processus de traitement du formulaire
	 * @return void
	 */
	public function process() : void
	{
		// On vérifit que le nom du formulaire correspont à celui qui a été soumis
		$nameSubmitted = getArray($this->_data, static::DATA_FORM_NAME);
		
		if($nameSubmitted == static::$name)
		{
			$validation = $this->_validation();
			$validation->validate();
			if($validation->success())
			{
				$this->_success = $this->_onValid();
			}
			else
			{
				$this->_errors = $this->_onErrors();
			}
		}
	}
	
	/**
	 * Méthode à exécuter si le formulaire est valide
	 * @return bool
	 */
	abstract protected function _onValid() : bool;
	
	/**
	 * Méthode à exécuter si le formulaire à des erreurs
	 * @return array Tableau des erreurs du formulaire
	 */
	protected function _onErrors() : array
	{
		$this->_success = FALSE;
		return $this->_validation()->errors();
	}
	
	/**
	 * Méthode à éxécuter si le traitement du formulaire est un succès
	 * @return void
	 */
	protected function _onSuccess() : void
	{
		// Rien pour le moment 
	}
	
	/**
	 * Retourne l'URL de redirection où rediriger en cas de succès
	 * @return string
	 */
	abstract public function redirectUrl() : ?string;
	
	/**********************************************************************************************************/
	
	/* RETOURNE, MODIFIT LES DONNEES */
	
	/**
	 * Retourne / modifit les données du formulaire
	 * @return array 
	 */
	protected function _data(?array $data = NULL) : array
	{
		if($data !== NULL)
		{
			foreach($data as $key => $value)
			{
				if(static::_allowedName($key))
				{
					$value = (is_string($value)) ? trim(strip_tags($value)) : $value;
					$this->_data[$key] = $value;
				}
			}
		}
		return $this->_data;
	}
	
	/**
	 * Retourne si le formulaire a pu être traité avec succès (reste à NULL si le formulaire n'a pas été posté)
	 * @return bool
	 */
	public function success() : ?bool
	{
		return $this->_success;
	}
	
	/**
	 * Retourne les erreurs de validation du formulaire
	 * @return array 
	 */
	protected function _errors() : array
	{
		return $this->_errors;
	}
	
	/**********************************************************************************************************/
	
	/* RENDU */
	
	/**
	 * Retourne le titre du formulaire
	 * @return string
	 */
	abstract public function title() : string;
	
	/**
	 * Retourne la réponse du traitement du formulaire (utilisé lors d'un appel Ajax notamment)
	 * @return array
	 *		'success': <boolean>,
	 *		'errors': <array>
	 */
	public function response() : array
	{
		$response = array(
			'success'	=> $this->success(),
			'errors'	=> $this->_errors(),
		);
		return $response;
	}
	
	/**
	 * Retourne le tableau des champs du formulaire
	 * @return array
	 */
	protected function _inputs() : array
	{
		$inputs = [
			'fields' 	=> [],
			'hidden'	=> [],
			'files'	 	=> [],	
			'name' 		=> (static::$name === NULL) ? NULL : FormHTML::hidden(static::DATA_FORM_NAME, static::$name),
			'submit' 	=> FormHTML::submit(static::$_submit_label, $this->_inputAttributes(static::DATA_SUBMIT)),
		];
		
		foreach(static::$_allowed_names as $name => $fieldType)
		{
			$key = ($fieldType == self::FIELD_FILE) ? 'files' : (($fieldType == self::FIELD_HIDDEN) ? 'hidden' : 'fields');
			$inputs[$key][$name] = $this->{ '_input' . ucfirst($fieldType) }($name);
		}
		
		
		
		return $inputs;
	}
	
	/**
	 * Retourne les attributs du champs dont le nom est en paramètre
	 * @param string $name
	 * @return array
	 */
	protected function _inputAttributes(string $name) : array
	{
		return []; // A surcharger dans les classes filles
	}
	
	/**
	 * Retourne un champs texte pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputText(string $name) : string
	{
		$value = getArray($this->_data, $name);
		
		$parameters = array_merge($this->_inputAttributes($name), [
			'autocomplete' => 'off',
			'id' => $name,
		]);
		
		$placeholder = getArray(static::$_placeholders, $name);
		if($placeholder !== NULL)
		{
			$parameters['placeholder'] = $placeholder;
		}
		
		return FormHTML::text($name, $value, $parameters);
	}
	
	/**
	 * Retourne un champs nombre pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputNumber(string $name) : string
	{
		$value = getArray($this->_data, $name);
		return FormHTML::number($name, $value, array_merge($this->_inputAttributes($name), [
			'autocomplete' => 'off',
			'id' => $name,
		]));
	}
	
	/**
	 * Retourne un champs mot de passe pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputPassword(string $name) : string
	{
		return FormHTML::input('password', $name, NULL, array_merge($this->_inputAttributes($name), [
			'id' => $name,
		]));
	}
	
	/**
	 * Retourne un champs date pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputDate(string $name) : string
	{
		$value = getArray($this->_data, $name);
		return FormHTML::input('date', $name, $value, array_merge($this->_inputAttributes($name), [
			'autocomplete' => 'off',
			'id' => $name,
		]));
	}
	
	/**
	 * Retourne un champs textarea pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputTextarea(string $name) : string
	{
		$value = getArray($this->_data, $name);
		return FormHTML::textarea($name, $value, array_merge($this->_inputAttributes($name), [
			'id' => $name,
		]));
	}
	
	/**
	 * Retourne un champs select pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputSelect(string $name) : string
	{
		$value = getArray($this->_data, $name);
		$options = $this->_selectOptions($name);
		
		if(count($options) == 0)
		{
			exception(strtr('Aucune options pour le champs :name.', [
				':name' => $name,
			]));
		}
		
		return FormHTML::select($name, $value, $options, array_merge($this->_inputAttributes($name), [
			'id' => $name,
		]));
	}
	
	/**
	 * Retourne les options du champs select dont le nom du champs est en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _selectOptions(string $name) : array
	{
		// A surcharger dans les classes filles
		return [];
	}
	
	/**
	 * Retourne un champs d'autocomplètion pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputAutocomplete(string $name) : string
	{
		$value = getArray($this->_data, $name);
		$autocompleteOptions = $this->_autocompleteOptions($name);
		return FormHTML::inputAutocomplete($name, $value, $autocompleteOptions);
	}
	
	/**
	 * Retourne les options du champs d'autocomplètion dont le nom du champs est en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _autocompleteOptions(string $name) : array
	{
		// A surcharger dans les classes filles
		return [];
	}
	
	/**
	 * Retourne les prévisualisation des champs (utilisé pour les champs de fichier)
	 * @return array
	 */
	protected function _inputPreviews() : array
	{
		// A surcharger dans les classes filles
		return [];
	}
	
	/**
	 * Retourne un champs de téléchargement de fichier pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputFile(string $name) : string
	{
		return FormHTML::file($name, [
			'id' => $name,
		]);
	}
	
	/**
	 * Retourne un champs caché pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function _inputHidden(string $name) : string
	{
		return FormHTML::hidden($name, getArray($this->_data, $name));
	}
	
	/**
	 * Retourne les labels des champs
	 * @return array
	 */
	protected function _labels() : array
	{
		$labels = [];
		
		foreach(static::$_labels as $key => $value)
		{
			$attributes = $this->_labelsAttributes($key);
			$labels[$key] = FormHTML::label($key, $value, $attributes);
		}
		
		
		
		return $labels;
	}
	
	/**
	 * Retourne les paramètres du labels dont la clé est en paramètre
	 * @param string $key 
	 * @return array
	 */
	protected function _labelsAttributes(string $key) : array
	{
		return []; // A gérer dans les classes filles
	}
	
	/**
	 * Retourne l'URL de soumission du formulaire
	 * @return string
	 */
	protected function _actionUrl() : ?string
	{
		return NULL;
	}
	
	/**
	 * Retourne les attributs du formulaire
	 * @return array
	 */
	protected function _attributes() : array
	{
		$attributes = [
			'method' => strtolower(static::$_method),
		];
		
		if(static::$_must_upload_files)
		{
			$attributes['enctype'] = 'multipart/form-data';
		}
		
		$actionUrl = $this->_actionUrl();
		if($actionUrl != NULL)
		{
			$attributes['action'] = $actionUrl;
		}
		
		$classes = [];
		
		if(static::$_render_inline)
		{
			$classes[] = 'inline';
		}
		
		if(count($this->_errors()) > 0)
		{
			$classes[] = 'errors';
		}
		
		if(count($classes) > 0)
		{
			$attributes['class'] = implode(' ', $classes);
		}
		
		return $attributes;
	}
	
	/**
	 * Retourne le nom de fichier de la vue
	 * @return string
	 */
	protected function _viewPath() : string
	{
		if(static::$_file_view === NULL)
		{
			static::$_file_view = 'forms/default';
		}
		return static::$_file_view;
	}
	
	/**
	 * Méthode de rendu du formulaire
	 * @return View 
	 */
	public function render() : View
	{
		$attributes = HTML::attributes($this->_attributes());
		$inputs = $this->_inputs();
		$labels = $this->_labels();
		$inputPreviews = $this->_inputPreviews();
		$inputGroupKeys = [ 'fields', 'files', ];
		
		$invertLabelsAndInputs = static::$_reverse_labels_inputs_position;
		
		foreach($inputGroupKeys as $inputGroupKey)
		{ 
			foreach($inputs[$inputGroupKey] as $inputKey => $input)
			{
				$label = getArray($labels, $inputKey);
				$preview = getArray($inputPreviews, $inputKey);
				$inputString = ($invertLabelsAndInputs) ? ($input . $preview . $label) : ($label . $preview . $input);
				$inputs[$inputGroupKey][$inputKey] = $inputString;
			}
		}

		return View::factory($this->_viewPath(), [
			'attributes' => $attributes,
			'errors' => $this->_errors(),
			'title'	=> $this->title(),
			'withTitle'	=> static::$_with_title,
			'inputs' => $inputs,
			'inputGroupKeys' => $inputGroupKeys,
		]);
	}
		
	/**********************************************************************************************************/
	
}
