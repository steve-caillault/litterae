<?php

/**
 * Formulaire de création et d'édition d'un livre
 */

namespace App\Forms\Admin\Books;

use Root\{ Validation };
/***/
use App\Forms\ProcessForm;
use App\Admin\{ Book, Collection, Editor };
use App\ImageResource;

class CreateOrEditForm extends ProcessForm {
	
	public const DATA_TITLE = 'title';
	public const DATA_EDITOR = 'editor';
	public const DATA_COLLECTION = 'collection';
	public const DATA_COVER = Book::IMAGE_COVER;
	public const DATA_BACK_COVER = Book::IMAGE_BACK_COVER;
	public const DATA_SPINNER = Book::IMAGE_SPINNER;
	
	/**
	 * Nom du formulaire
	 * @var string
	 */
	public static string $name = 'admin-book-create-or-edit';
	
	/**
	 * Noms de champs autorisés
	 * @var array
	 */
	protected static array $_allowed_names = [
		self::DATA_TITLE => self::FIELD_TEXT,
		self::DATA_EDITOR => self::FIELD_AUTOCOMPLETE,
		self::DATA_COLLECTION => self::FIELD_AUTOCOMPLETE,
		self::DATA_COVER => self::FIELD_FILE,
		self::DATA_BACK_COVER => self::FIELD_FILE,
		self::DATA_SPINNER => self::FIELD_FILE,
	];
	
	/**
	 * Données du formulaire
	 * @var array
	 */
	protected array $_data = [
		self::DATA_TITLE => NULL,
		self::DATA_EDITOR => NULL,
		self::DATA_COLLECTION => NULL,
		self::DATA_COVER => NULL,
		self::DATA_BACK_COVER => NULL,
		self::DATA_SPINNER => NULL,
	];
	
	/**
	 * Listes des labels
	 * @var array
	 */
	protected static array $_labels = [
		self::DATA_TITLE => 'Titre',
		self::DATA_EDITOR => 'Editeur',
		self::DATA_COLLECTION => 'Collection',
		self::DATA_COVER => 'Couverture',
		self::DATA_BACK_COVER => 'Quatrième de couverture',
		self::DATA_SPINNER => 'Tranche',
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
	 * Livre qui est édité
	 * @var Book
	 */
	private ?Book $_book = NULL;
	
	/***********************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur
	 * @param array $params Paramètres
	 * @return array
	 */
	protected function __construct(array $params)
	{
		$this->_book = getArray($params, 'book', $this->_book);
		if($this->_book !== NULL AND ! ($this->_book instanceof Book))
		{
			exception('Livre incorrect.');
		}
		
		$data = getArray($params, 'data');
		if($this->_book !== NULL)
		{
			$params['data'] = array_merge($this->_data, $this->_book->asArray(), $data);
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
			self::DATA_TITLE => [
				array('required'),
				array('min_length', array('min' => 3)),
				array('max_length', array('max' => 100)),
			],
			self::DATA_EDITOR => [
				array('model_exists', [
					'class' => Editor::class,
					'criterias' => [
						'id' => getArray($this->_data, self::DATA_EDITOR),
					],
				]),
			],
			self::DATA_COLLECTION => [
				array('model_exists', [
					'class' => Collection::class,
					'criterias' => [
						'id' => getArray($this->_data, self::DATA_COLLECTION),
					],
				]),
			],
			self::DATA_COVER => [
				array('upload_valid'),
				array('upload_extensions', array('types' => [ 'jpg', 'png', ])),
				array('upload_size', array('size' => 1)),
			],
			self::DATA_BACK_COVER => [
				array('upload_valid'),
				array('upload_extensions', array('types' => [ 'jpg', 'png', ])),
				array('upload_size', array('size' => 1)),
			],
			self::DATA_SPINNER => [
				array('upload_valid'),
				array('upload_extensions', array('types' => [ 'jpg', 'png', ])),
				array('upload_size', array('size' => 0.5)),
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
		
		$imageTypes = [ self::DATA_COVER, self::DATA_BACK_COVER, self::DATA_SPINNER, ]; 
		foreach($imageTypes as $imageType)
		{
			unset($data[$imageType]);
		}
		
		$collectionId = getArray($data, self::DATA_COLLECTION);
		if($collectionId != NULL)
		{
			unset($data[self::DATA_EDITOR]); // Pour éviter la redondance en base de données 
		}
		
		$editorId = getArray($data, self::DATA_EDITOR);
		if($editorId != NULL)
		{
			unset($data[self::DATA_COLLECTION]); // Pour éviter la redondance en base de données 
		}
		
		// La collection ou l'éditeur doivent-être connu
		if($collectionId == NULL AND $editorId == NULL)
		{
			$this->_validation()->addError(self::DATA_COLLECTION, 'required', 'La collection ou l\'éditeur sont obligatoires.');
			$this->_errors = $this->_onErrors();
			return FALSE;
		}
		
		// Fusion des données avec celles du livre que l'on édite
		if($this->_book !== NULL)
		{
			$data = array_merge($this->_book->asArray(), $data);
		}
		
		$book = Book::factory($data);
		
		$success = $book->save();
		
		if($book->id !== NULL)
		{
			// Téléchargement des images
			foreach($imageTypes as $imageType)
			{
				$imageFile = getArray($this->_data, $imageType);
				if($imageFile !== NULL)
				{
					$book->uploadImage($imageType, $imageFile);
				}
			}
		}
		
		if($success AND $this->_book !== NULL)
		{
			$this->_book = $book;
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
			return Book::adminListUri();
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
		$images = [
			self::DATA_COVER => NULL,
			self::DATA_BACK_COVER => NULL,
			self::DATA_SPINNER => NULL,
		];
		
		$fieldnames = array_keys($images);
		
		if($this->_book !== NULL)
		{
			foreach($fieldnames as $fieldname)
			{
				$images[$fieldname] = $this->_book->image($fieldname, ImageResource::VERSION_SMALL, [
					'title' => strtr('Image de la :type du livre :title.', [
						':type' => translate(strtr($fieldname, [
							'_' => ' ',
						])),
						':title' => $this->_book->title,
					]),
				]);
			}
		}
		return array_merge(parent::_inputPreviews(), $images);
	}
	
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
		elseif($name == self::DATA_COLLECTION)
		{
			$collectionId = getArray($this->_data, self::DATA_COLLECTION);
			return array_merge(Collection::adminSearchField($collectionId), [
				'text_id' => self::DATA_COLLECTION,
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
		if($this->_book !== NULL)
		{
			return strtr('Modification du livre :title', [ ':title' => $this->_book->title ]);
		}
		return 'Création d\'un livre';
	}
	
	/***********************************************************/
	
}