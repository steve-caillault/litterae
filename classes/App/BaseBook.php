<?php

/**
 * Gestion d'un livre de base
 */

namespace App;

use App\Collection\{
	ContributorCollection
};

abstract class BaseBook extends ModelWithImageUpload {
	
	public const IMAGE_COVER = 'cover';
	public const IMAGE_BACK_COVER = 'back_cover';
	public const IMAGE_SPINNER = 'spinner';
	
	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'books';
	
	/**
	 * Classe de la collection des contributeurs à utiliser
	 * @var string
	 */
	protected static string $_contributor_collection_class = ContributorCollection::class;
	
	/**
	 * Classe de l'objet collection à utiliser
	 * @var string
	 */
	protected static string $_collection_class = Collection::class;
	
	/**
	 * Classe de l'objet éditeur à utiliser
	 * @var string
	 */
	protected static string $_editor_class = Editor::class;
	
	/**
	 * Formats des images
	 * @var array
	 */
	protected static array $_images_formats = [
		self::IMAGE_COVER => [
			ImageResource::VERSION_SMALL => [
				'width'	=> 200,
				'height' => 300,
			],
			ImageResource::VERSION_MEDIUM => [
				'width' => 400,
				'height' => 600,
			],
		],
		self::IMAGE_BACK_COVER => [
			ImageResource::VERSION_SMALL => [
				'width'	=> 200,
				'height' => 300,
			],
			ImageResource::VERSION_MEDIUM => [
				'width' => 400,
				'height' => 600,
			],
		],
		self::IMAGE_SPINNER => [
			ImageResource::VERSION_SMALL => [
				'height' => 300,
			],
			ImageResource::VERSION_MEDIUM => [
				'height' => 600,
			],
		],
	];
	
	/*********************************************************************/
	
	/* CHAMPS EN BASE DE DONNEES */
	
	/**
	 * Identifiant de l'auteur en base de données
	 * @var int
	 */
	public ?int $id = NULL;
	
	/**
	 * Titre du livre
	 * @var string
	 */
	public ?string $title = NULL;
	
	/**
	 * Identifiant de l'éditeur du livre
	 * @var int
	 */
	public ?int $editor = NULL;
	
	/**
	 * Identifiant en base de données de la collection
	 * @var int
	 */
	public ?int $collection = NULL;
	
	/**
	 * Nom du fichier de la couverture
	 * @var int
	 */
	public ?int $cover = NULL;
	
	/**
	 * Nom du fichier de la quatrième de couverture
	 * @var int
	 */
	public ?int $back_cover = NULL;
	
	/**
	 * Nom du fichier de la tranche
	 * @var int
	 */
	public ?int $spinner = NULL;
	
	/*********************************************************************/
	
	/**
	 * Auteurs du livre
	 * @var array
	 */
	private ?array $_authors = NULL;
	
	/**
	 * Traducteurs du livre
	 * @var array
	 */
	private ?array $_translators = NULL;
	
	/**
	 * Illustrateurs du livre
	 * @var array
	 */
	private ?array $_illustrators = NULL;
	
	/**
	 * Editeur du livre
	 * @var Editor
	 */
	private ?Editor $_editor = NULL;
	
	/**
	 * Collection du livre
	 * @var Collection
	 */
	private ?Collection $_collection = NULL;
	
	/*********************************************************************/
	
	/* GET */
	
	/**
	 * Retourne les auteurs du livre
	 * @return array
	 */
	public function authors() : array
	{
		if($this->_authors === NULL)
		{
			$this->_authors = static::$_contributor_collection_class::factory()->book($this)->type(Contributor::TYPE_AUTHOR)->get();
		}
		return $this->_authors;
	}
	
	/**
	 * Retourne les traducteurs du livre
	 * @return array
	 */
	public function translators() : array
	{
		if($this->_translators === NULL)
		{
			$this->_translators = static::$_contributor_collection_class::factory()->book($this)->type(Contributor::TYPE_TRANSLATOR)->get();
		}
		return $this->_translators;
	}
	
	/**
	 * Retourne les illustrateurs du livre
	 * @return array
	 */
	public function illustrators() : array
	{
		if($this->_illustrators === NULL)
		{
			$this->_illustrators = static::$_contributor_collection_class::factory()->book($this)->type(Contributor::TYPE_ILLUSTRATOR)->get();
		}
		return $this->_illustrators;
	}
	
	/**
	 * Retourne l'éditeur du livre
	 * @return Editor
	 */
	public function editor() : Editor
	{
		if($this->_editor === NULL)
		{
			$collection = $this->collection();
			if($collection !== NULL)
			{
				$this->_editor = $collection->editor();
			}
			else
			{
				$this->_editor = static::$_editor_class::factory($this->editor);
			}
		}
		return $this->_editor;
	}
	
	/**
	 * Retourne la collection du livre
	 * @return Collection
	 */
	public function collection() : ?Collection
	{
		if($this->_collection === NULL AND $this->collection !== NULL)
		{
			$this->_collection = static::$_collection_class::factory($this->collection);
		}
		return $this->_collection;
	}
	
	/*********************************************************************/
	
}