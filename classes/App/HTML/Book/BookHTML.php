<?php

/**
 * Gestion du HTML d'un livre
 */

namespace App\HTML\Book;

use Root\{ Instanciable, HTML };
/***/
use App\{ Book, BookList, Contributor };

class BookHTML extends Instanciable {
	
	/**
	 * Livre à gérer
	 * @var Book
	 */
	private Book $_book;
	
	/*********************************************************/
	
	/**
	 * Constructeur
	 * @param Book $book
	 */
	protected function __construct(Book $book)
	{
		$this->_book = $book;
	}
	
	/*********************************************************/
	
	/**
	 * Retourne le tableau des contributeurs formatés
	 * @return array
	 */
	public function contributors() : array
	{
		$book = $this->_book;
		
		$typesByKey = [
			'authors' => Contributor::TYPE_AUTHOR,
			'translators' => Contributor::TYPE_TRANSLATOR,
			'illustrators' => Contributor::TYPE_ILLUSTRATOR,
		];
		
		$contributors = [
			'authors' => [
				'title' => 'author',
				'items' => [],
			],
			'translators' => [
				'title' => 'translator',
				'items' => [],
			],
			'illustrators' => [
				'title' => 'illustrator',
				'items' => [],
			],
		];
		
		$types = array_keys($contributors);
		foreach($types as $type)
		{
			$collection = $book->{ $type }();
			foreach($collection as $person)
			{
				$name = $person->fullName();
				$contributorUri = $person->contributor($book, $typesByKey[$type])->booksUri();
				$name = HTML::anchor($contributorUri, $name, [
					'title' => strtr('Consulter la liste des livres de :name.', [
						':name' => $name,
					]),
				]);
				
				$contributors[$type]['items'][] = $name;
			}
			
			$count = count($contributors[$type]['items']);
			if($count == 0)
			{
				unset($contributors[$type]);
				continue;
			}
			
			$contributors[$type]['title'] = translate($contributors[$type]['title'], [
				'count' => $count,
			]);
		}
		
		return $contributors;
	}
	
	/**
	 * Retourne le tableau des listes où apparaît le livre
	 * @return array
	 */
	public function lists() : array
	{
		$book = $this->_book;
		$lists = [];
		$types = BookList::allowedTypes();
		foreach($types as $list)
		{
			$textList = strtolower($list);
			$texttListTranslated = translate($textList, [ 'count' => 2, ]);
			$addDescription = strtr('Ajouter :book à la liste des livres :list.', [
				':book' => $book->title,
				':list' => $texttListTranslated,
			]);
			$deleteDescription = strtr('Supprimer :book de la liste des livres :list.', [
				':book' => $book->title,
				':list' => $texttListTranslated,
			]);
			
			$classes = [ 'manage-list', ];
			
			$selected = $this->_book->inList($list);
			if($selected)
			{
				$description = $deleteDescription;
				$classes[] = 'selected';
			}
			else
			{
				$description = $addDescription;
			}
			
			$lists[] = HTML::tag('button', [
				'type' => 'button',
				'class' => implode(' ', $classes),
				'content' => translate($textList, [ 'count' => 0, ]),
				'title' => $description,
				'data-type' => ($selected) ? 'delete' : 'add',
				/***/
				'data-url-delete' => getURL($book->deleteFromListUri($list)),
				'data-url-add' => getURL($book->addToListUri($list)),
				/***/
				'data-description-add' => $addDescription,
				'data-description-delete' => $deleteDescription,
			]);
		}
		
		return $lists;
	}

	/*********************************************************/
	
}