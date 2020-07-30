<?php

/**
 * Gestion d'un contributeur à l'écriture d'un livre depuis le panneau d'administration
 */

namespace App\Admin;

use Root\Route;
/***/
use App\BaseContributor as ContributorModel;

class Contributor extends ContributorModel {
	
	/**
	 * Class de l'objet personne à utiliser
	 * @var string
	 */
	protected static string $_person_class = Person::class;
	
	/*********************************************************************/
	
	/**
	 * Retourne les paramètres pour les URI du contributeur
	 * @return array
	 */
	private function _uriParams() : array
	{
		return [
			'bookId' => $this->book,
			'contributorType' => strtolower($this->type),
			'personId' => $this->person,
		];
	}
	
	/**
	 * Retourne l'URI de la suppression du contributeur
	 * @return string
	 */
	public function adminDeleteUri() : string
	{
		return Route::retrieve('admin.books.contributors.delete')->uri($this->_uriParams());
	}
	
	/**
	 * Retourne l'URI Ajax de la suppresion du contributeur
	 * @return string
	 */
	public function adminAjaxDeleteUri() : string
	{
		return Route::retrieve('admin.books.contributors.delete.ajax')->uri($this->_uriParams());
	}
	
	/*********************************************************************/
	
}