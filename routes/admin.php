<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Routes du panneau d'administration
 */

use Root\Route;
use App\Contributor;

/* Authentification */

/**
 * Page d'index
 */
Route::add('admin', 'admin', 'Admin\HomeController@index');

/**
 * Page de connexion
 */
Route::add('admin.login', 'admin/login', 'Admin\Auth\LoginController@index');

/**
 * Page de déconnexion
 */
Route::add('admin.logout', 'admin/logout', 'Admin\Auth\LogoutController@index');

/*******************************************************************/

/* Pays */

/**
 * Liste des pays
 */
Route::add('admin.countries.list', 'admin/countries(/page-{page})', 'Admin\Countries\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Ajout d'un pays
 */
Route::add('admin.countries.add', 'admin/countries/add', 'Admin\Countries\CreateOrEditController@index');

/**
 * Edition d'un pays
 */
Route::add('admin.countries.edit', 'admin/countries/{countryCode}/edit', 'Admin\Countries\CreateOrEditController@index')->where([
	'countryCode' => '[a-z]{2}',
]);

/*******************************************************************/

/* Livres */

/**
 * Liste des livres
 */
Route::add('admin.books.list', 'admin/books(/page-{page})', 'Admin\Books\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Ajout d'un livre
 */
Route::add('admin.books.add', 'admin/books/add', 'Admin\Books\CreateOrEditController@index');

/**
 * Edition d'un livre
 */
Route::add('admin.books.edit', 'admin/books/{bookId}/edit', 'Admin\Books\CreateOrEditController@index')->where([ 
	'bookId' => '[0-9]+', 
]);

/**
 * Recherche d'un livre
 */
Route::add('admin.books.search', 'admin/books/search', 'Admin\Books\SearchController@index');

/* Contributeur d'un livre */

/**
 * Ajout d'un contributeur (Ajax)
 */
Route::add('admin.books.contributors.add', 'admin/books/{bookId}/{contributorType}/add', 'Admin\Books\Contributors\AjaxController@add')->where([
	'bookId' => '[0-9]+',
	'contributorType' => strtr('(:lists)+', [
		':lists' => strtolower(implode('|', Contributor::allowedTypes())),
	]),
]);

/**
 * Suppression d'un contributeur
 */
Route::add('admin.books.contributors.delete', 'admin/books/{bookId}/{contributorType}/{personId}/delete', 'Admin\Books\Contributors\DeleteController@index')->where([
	'bookId' => '[0-9]+',
	'contributorType' => strtr('(:lists)+', [
		':lists' => strtolower(implode('|', Contributor::allowedTypes())),
	]),
	'personId' => '[0-9]+',
]);

/**
 * Suppression d'un contributeur (Ajax)
 */
Route::add('admin.books.contributors.delete.ajax', 'admin/books/{bookId}/{contributorType}/{personId}/delete/ajax', 'Admin\Books\Contributors\AjaxController@delete')->where([
	'bookId' => '[0-9]+',
	'contributorType' => strtr('(:lists)+', [
		':lists' => strtolower(implode('|', Contributor::allowedTypes())),
	]),
	'personId' => '[0-9]+',
]);

/*******************************************************************/

/* Personnes */

/**
 * Liste des personnes
 */
Route::add('admin.persons.list', 'admin/persons(/page-{page})', 'Admin\Persons\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Ajout d'une personnes
 */
Route::add('admin.persons.add', 'admin/persons/add', 'Admin\Persons\CreateOrEditController@index');

/**
 * Edition d'une personne
 */
Route::add('admin.persons.edit', 'admin/persons/{personId}/edit', 'Admin\Persons\CreateOrEditController@index')->where([
	'personId' => '[0-9]+', 
]);

/*******************************************************************/

/* Editeurs */

/**
 * Liste des éditeurs
 */
Route::add('admin.editors.list', 'admin/editors(/page-{page})', 'Admin\Editors\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Ajout d'un éditeur
 */
Route::add('admin.editors.add', 'admin/editors/add', 'Admin\Editors\CreateOrEditController@index');

/**
 * Edition d'un éditeur
 */
Route::add('admin.editors.edit', 'admin/editors/{editorId}/edit', 'Admin\Editors\CreateOrEditController@index')->where([
	'editorId' => '[0-9]+',
]);

/*******************************************************************/

/* Collections */

/**
 * Liste des collections
 */
Route::add('admin.collections.list', 'admin/collections(/page-{page})', 'Admin\Collections\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Ajout d'une collection
 */
Route::add('admin.collections.add', 'admin/collections/add', 'Admin\Collections\CreateOrEditController@index');

/**
 * Edition d'une collection
 */
Route::add('admin.collections.edit', 'admin/collections/{collectionId}/edit', 'Admin\Collections\CreateOrEditController@index')->where([
	'collectionId' => '[0-9]+',
]);

/*******************************************************************/

/**
 * Recherche par autocomplètion
 */
Route::add('admin.ajax.search', 'admin/ajax/search', 'Admin\AjaxController@search');

/*******************************************************************/
