<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Définition des routes
 */

use Root\Route;

require('routes/admin.php');
require('routes/site.php');

/* Authentification */

/**
 * Page de connexion
 */
Route::add('login', 'login', 'Auth\LoginController@index');

/**
 * Page de déconnexion
 */
Route::add('logout', 'logout', 'Auth\LogoutController@index');

/*******************************************************************/

/**
 * Page d'erreur
 */
Route::add('error', 'error', 'ErrorController@index');

/**
 * Route de test
 */
Route::add('testing', 'testing', 'TestingController@index');

/*******************************************************************/

/**
 * Image gérée en base de données
 */
Route::add('resources.image', 'images/{file}', 'ResourceController@image')->where([
	'file' => '([^\/])+',
]);

/*******************************************************************/
