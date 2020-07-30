<?php

/**
 * Gestion d'un livre depuis le panneau d'administration
 */

namespace App\Admin;

use Root\Route;
/***/
use App\BaseBook;
use App\Admin\Collection\ContributorCollection;

class Book extends BaseBook {
	
	/**
	 * Classe de la collection des contributeurs à utiliser
	 * @var string
	 */
	protected static string $_contributor_collection_class = ContributorCollection::class;
	
	/**
	 * Classe de l'objet collection à utiliser
	 */
	protected static string $_collection_class = Collection::class;
	
	/**
	 * Classe de l'objet éditeur à utiliser
	 * @var string
	 */
	protected static string $_editor_class = Editor::class;
	
	/**************************************************************************/
	
	/* URI */
	
	/**
	 * Retourne l'URI de la liste des livres du panneau d'administration
	 * @return string
	 */
	public static function adminListUri() : string
	{
		return Route::retrieve('admin.books.list')->uri();
	}
	
	/**
	 * Retourne l'URI de création d'un livre
	 * @return string
	 */
	public static function adminAddUri() : string
	{
		return Route::retrieve('admin.books.add')->uri();
	}
	
	/**
	 * Retourne l'URI de modification d'un livre
	 * @return string
	 */
	public function adminEditUri() : string
	{
		return Route::retrieve('admin.books.edit')->uri([
			'bookId' => $this->id,
		]);
	}
	
	/**
	 * Retourne l'URI d'ajout d'un contributeur dont le type est en paramètre
	 * @param string $type
	 * @return string
	 */
	public function adminAddContributorUri(string $type) : string
	{
		return Route::retrieve('admin.books.contributors.add')->uri([
			'bookId' => $this->id,
			'contributorType' => strtolower($type),
		]);
	}
	
	/**************************************************************************/
	
}