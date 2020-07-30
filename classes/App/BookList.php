<?php

/**
 * Gestion d'un livre dans une liste de livres (lectures, souhaits, possédés)
 */

namespace App;

use App\Traits\Models\{ WithBook, WithReader };

class BookList extends Model {
	
	use WithBook, WithReader;
	
	public const TYPE_READING = 'READING';
	public const TYPE_OWNERSHIP = 'OWNERSHIP';
	public const TYPE_WISH = 'WISH';
	
	/**********************************************************/
	
	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'books_lists';
	
	/**
	 * Clé primaire
	 * @var string
	 */
	protected static string $_primary_key = 'book|reader|type';
	
	/**
	 * Vrai si la clé primaire est un auto-incrément
	 * @var bool
	 */
	protected static bool $_autoincrement = FALSE;
	
	/**********************************************************/
	
	/**
	 * Type de la liste où apparaît le livre
	 * @var string
	 */
	public ?string $type = NULL;
	
	/**
	 * Types autorisés
	 * @var array
	 */
	private static array $_allowed_types = [
		self::TYPE_READING,
		self::TYPE_OWNERSHIP,
		self::TYPE_WISH,
	];
	
	/**
	 * Classe de l'objet livre à utiliser
	 * @var string
	 */
	protected static string $_book_class = Book::class;
	
	/**********************************************************/
	
	/**
	 * Retourne les types autorisés
	 * @return array
	 */
	public static function allowedTypes() : array
	{
		return static::$_allowed_types;
	}
	
	/**********************************************************/
	
}