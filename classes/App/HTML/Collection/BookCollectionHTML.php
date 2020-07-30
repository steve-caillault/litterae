<?php

/**
 * Gestion HTML d'une liste de livres
 */

namespace App\HTML\Collection;

use Root\{ Route, HTML };
/***/
use App\{ Model, Book, Reader, BookList, Person, Contributor, Editor, Collection as CollectionEditor };
use App\Collection\{ BookCollection, BookListCollection };
use App\HTML\Book\BookHTML;
use App\ImageResource;

class BookCollectionHTML extends CollectionHTML {

	use Traits\WithImagesLoading;
	
	/**
	 * Type de tri
	 * @var string
	 */
	protected ?string $_order_by = BookCollection::ORDER_BY_TITLE;
	
	/**
	 * Sens de direction du tri
	 * @var string
	 */
	protected string $_direction = BookCollection::DIRECTION_ASC;
	
	/**
	 * Lecteur connecté
	 * @var Reader
	 */
	private Reader $_reader;
	
	/**
	 * Auteur à filtrer
	 * @var Person
	 */
	private ?Person $_author = NULL;
	
	/**
	 * Traducteur à filtrer
	 * @var Person
	 */
	private ?Person $_translator = NULL;
	
	/**
	 * Illustrateur à filtrer
	 * @var Person
	 */
	private ?Person $_illustrator = NULL;
	
	/**
	 * Editeur à filtrer
	 * @var Editor
	 */
	private ?Editor $_editor = NULL;
	
	/**
	 * Collection à filtrer
	 * @var CollectionEditor
	 */
	private ?CollectionEditor $_collection_editor = NULL;
	
	/**
	 * Nom de la vue utilisée pour l'affichage
	 * @var string
	 */
	protected string $_view_name = 'items/books';
	
	/**
	 * Type de la liste de livre à filtrer
	 * @var string
	 */
	private ?string $_book_list_type = NULL;
	
	/**
	 * Liste des livres de la sélection
	 * @var array
	 */
	private ?array $_books_lists = NULL;
	
	/**
	 * Liste des identifiants des livres sélectionnés
	 * @var array
	 */
	private ?array $_book_ids = NULL;
	
	/*****************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur
	 * @param array $params
	 */
	protected function __construct(array $params = [])
	{
		// Récupération du lecteur
		$reader = getArray($params, 'reader');
		if(! $reader instanceof Reader)
		{
			exception('Lecteur incorrect.');
		}
		$this->_reader = $reader;
		
		// Récupération de l'auteur
		$this->_author = getArray($params, 'author');
		if($this->_author !== NULL AND ! $this->_author instanceof Person)
		{
			exception('Auteur incorrect.');
		}
		
		// Récupération du traducteur
		$this->_translator = getArray($params, 'translator');
		if($this->_translator !== NULL AND ! $this->_translator instanceof Person)
		{
			exception('Traducteur incorrect.');
		}
		
		// Récupération de l'illustrateur
		$this->_illustrator = getArray($params, 'illustrator');
		if($this->_illustrator !== NULL AND ! $this->_illustrator instanceof Person)
		{
			exception('Illustrateur incorrect.');
		}
		
		// Récupération de l'éditeur
		$this->_editor = getArray($params, 'editor');
		if($this->_editor !== NULL AND ! $this->_editor instanceof Editor)
		{
			exception('Editeur incorrect.');
		}
		
		// Récupération de la collection
		$this->_collection_editor = getArray($params, 'collection');
		if($this->_collection_editor !== NULL AND ! $this->_collection_editor instanceof CollectionEditor)
		{
			exception('Collection incorrecte.');
		}
		
		// Récupération de la liste de livre à filtrer
		$this->_book_list_type = getArray($params, 'book_list_type') ?? NULL;
		if($this->_book_list_type != NULL AND (! is_string($this->_book_list_type) OR ! in_array($this->_book_list_type, BookList::allowedTypes())))
		{
			exception('Liste de livres incorrecte.');
		}
		
		parent::__construct($params);
	}
	
	/*****************************************************************/
	
	/* GESTION DE LA LISTE */
	
	/**
	 * Initialisation de la collection
	 * @return BookCollection
	 */
	protected function _initCollection() : BookCollection
	{
		$collection = BookCollection::factory();
		
		if($this->_author !== NULL)
		{
			$collection->contributor($this->_author, Contributor::TYPE_AUTHOR);
		}
		
		if($this->_translator !== NULL)
		{
			$collection->contributor($this->_translator, Contributor::TYPE_TRANSLATOR);
		}
		
		if($this->_illustrator !== NULL)
		{
			$collection->contributor($this->_illustrator, Contributor::TYPE_ILLUSTRATOR);
		}
		
		if($this->_editor !== NULL)
		{
			$collection->editor($this->_editor);
		}
		
		if($this->_collection_editor !== NULL)
		{
			$collection->collection($this->_collection_editor);
		}
		
		if($this->_book_list_type !== NULL)
		{
			$collection->bookList($this->_reader, $this->_book_list_type);
		}
		
		return $collection;
	}
	
	/**
	 * Retourne les identifiants des livres sélectionnés
	 * @var array
	 */
	private function _bookIds() : array
	{
		if($this->_book_ids === NULL)
		{
			$books = $this->_collection();
			foreach($books as $book)
			{
				$bookIds[] = $book->id;
			}
			$this->_book_ids = $bookIds;
		}
		return $this->_book_ids;
	}
	
	/**
	 * Retourne les listes où apparait le livre
	 * @return array
	 */
	private function _bookLists(Book $book) : array
	{
		if($this->_books_lists === NULL)
		{
			$lists = [];
			$bookIds = $this->_bookIds();
			if(count($bookIds) > 0)
			{
				$collection = BookListCollection::factory()->bookIds($bookIds)->reader($this->_reader)->get();
				foreach($collection as $list)
				{
					$lists[$list->book][] = $list;
				}
			}
			
			$this->_books_lists = $lists;
		}
		
		return getArray($this->_books_lists, $book->id, []);
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
		// Récupération des listes auxquelles le livre appartient
		$bookList = $this->_bookLists($model);
		$model->bookLists($bookList);
		/***/
		$bookHTML = BookHTML::factory($model);
		/***/
		// Ancre de l'éditeur
		$editor = $model->editor();
		$editorUri = Route::retrieve('editors.item')->uri([
			'editorId' => $editor->id,
		]);
		$editorAnchor = HTML::anchor($editorUri, $editor->name, [
			'title' => strtr('Consulter la liste des livres de l\'éditeur :name.', [
				':name' => $editor->name,
			]),
		]);
		/***/
		// Ancre de la collection
		$collection = $model->collection();
		$collectionAnchor = NULL;
		if($collection !== NULL)
		{
			$collectionUri = Route::retrieve('collections.item')->uri([
				'collectionId' => $collection->id,
			]);
			$collectionAnchor = HTML::anchor($collectionUri, $collection->name, [
				'title' => strtr('Consulter la liste des livres de la collection :name.', [
					':name' => $collection->name,
				]),
			]);
		}
		/***/
		// Liste des images
		$images = [];
		$resourceImages = $this->_images($model);
		foreach($resourceImages as $type => $resourceImage)
		{
			$mediumImage = getArray($resourceImage, ImageResource::VERSION_MEDIUM);
			$smallImage = getArray($resourceImage, ImageResource::VERSION_SMALL);
			if($mediumImage === NULL OR $smallImage === NULL)
			{
				continue;
			}
			
			$styles = 'background-image: url(' . $smallImage->url() . ');';
			
			$attributes = HTML::attributes([
				'class' => 'item' . ((count($images) > 0) ? ' hidden' : NULL),
				'style' => $styles,
				'data-url' => $mediumImage->url(), // getURL($mediumImageUrl),
			]);
			
			$images[$type] = strtr('<div :attributes></div>', [
				':attributes' => $attributes,
			]); 
		}
		/***/
		$classes = [ 'item', 'book', ];
		if(count($images) == 0)
		{
			$classes[] = 'without-image';
		}
		$attributes = HTML::attributes([
			'class' => implode(' ', $classes),
		]);
		/***/
		$data = [
			'attributes' => $attributes,
			'title' => $model->title,
			'editor' => $editorAnchor,
			'collection' => $collectionAnchor,
			'contributors' => $bookHTML->contributors(),
			'images' => $images,
			'lists' => $bookHTML->lists(),
		];
		/***/
		return $data;
	}
	
	/**
	 * Retourne la phrase lorsqu'il n'y a pas d'objet
	 * @return string
	 */
	protected function noItemSentence() : ?string
	{
		return 'Aucun livre n\'a été trouvé.';
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
	
}