<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Routes du site
 */

use Root\Route;
/***/
use App\{ BookList, Contributor };

/**
 * Page d'accueil
 */
Route::add('home', '(page-{page})', 'Site\HomeController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/*******************************************************************/

/**
 * Liste des auteurs
 */
Route::add('authors', 'authors(/page-{page})', 'Site\Books\Authors\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Liste des livres d'un auteur
 */
Route::add('authors.item', 'authors/{authorId}(/page-{page})', 'Site\Books\Authors\BooksController@index')->where([
	'authorId' => '[0-9]+',
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/*******************************************************************/

/**
 * Liste des traducteurs
 */
Route::add('translators', 'translators(/page-{page})', 'Site\Books\Translators\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Liste des livres d'un traducteur
 */
Route::add('translators.item', 'translators/{translatorId}(/page-{page})', 'Site\Books\Translators\BooksController@index')->where([
	'translatorId' => '[0-9]+',
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/*******************************************************************/

/**
 * Liste des illustrateurs
 */
Route::add('illustrators', 'illustrators(/page-{page})', 'Site\Books\Illustrators\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Liste des livres d'un illustrateur
 */
Route::add('illustrators.item', 'illustrators/{illustratorId}(/page-{page})', 'Site\Books\Illustrators\BooksController@index')->where([
	'illustratorId' => '[0-9]+',
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/*******************************************************************/

/**
 * Liste des éditeurs
 */
Route::add('editors', 'editors(/page-{page})', 'Site\Books\Editors\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Listes des livres d'un éditeur
 */
Route::add('editors.item', 'editors/{editorId}(/page-{page})', 'Site\Books\Editors\BooksController@index')->where([
	'editorId' => '[0-9]+',
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/*******************************************************************/

/**
 * Liste des collections
 */
Route::add('collections', 'collections(/page-{page})', 'Site\Books\Collections\ListController@index')->where([
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/**
 * Listes des livres d'une collection
 */
Route::add('collections.item', 'collections/{collectionId}(/page-{page})', 'Site\Books\Collections\BooksController@index')->where([
	'collectionId' => '[0-9]+',
	'page' => '[0-9]+',
])->defaults([
	'page' => 1,
]);

/*******************************************************************/

/* Gestion des listes d'un livre */

/**
 * Ajout du livre à une liste
 */
Route::add('books.list.add', 'books/{bookId}/lists/{bookList}/add', 'Site\Books\ListsController@add')->where([
	'bookId' => '[0-9]+',
	'bookList' => strtr('(:lists)+', [ 
		':lists' => strtolower(implode('|', BookList::allowedTypes())),
	]),
]);

/**
 * Supprime le livre d'une liste
 */
Route::add('books.list.delete', 'books/{bookId}/lists/{bookList}/delete', 'Site\Books\ListsController@delete')->where([
	'bookId' => '[0-9]+',
	'bookList' => strtr('(:lists)+', [
		':lists' => strtolower(implode('|', BookList::allowedTypes())),
	]),
]);

/*******************************************************************/

/**
 * Ajoute le contributeur à la liste à suivre
 */
Route::add('persons.follow', 'persons/{contributorType}/{personId}/follow', 'Site\Persons\AjaxController@follow')->where([
	'personId' => '[0-9]+',
	'contributorType' => strtr('(:lists)+', [
		':lists' => strtolower(implode('|', Contributor::allowedTypes())),
	]),
]);

/**
 * Retire le contributeur de la liste à suivre
 */
Route::add('persons.unfollow', 'persons/{contributorType}/{personId}/unfollow', 'Site\Persons\AjaxController@unfollow')->where([
	'personId' => '[0-9]+',
	'contributorType' => strtr('(:lists)+', [
		':lists' => strtolower(implode('|', Contributor::allowedTypes())),
	]),
]);

/*******************************************************************/