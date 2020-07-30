<?php

/**
 * Gestion des fichiers stockés en base de données
 */

namespace App\Controllers;

use Root\{ Controller, Response };
/***/
use App\FileResource;

class ResourceController extends Controller {
	
	/**
	 * Fichier à charger
	 * @var FileResource
	 */
	private FileResource $_file;
	
	/*****************************************************/
	
	/**
	 * Méthode à éxécuter avant la méthode principale du contrôleur
	 * @return void
	 */
	public function before() : void
	{
		// Récupération de l'identifiant du fichier
		$filename = $this->request()->parameter('file');
		$file = FileResource::findByPublicId($filename);
		
		if($file === NULL)
		{
			exception('Image introuvable.', 404);
		}
		
		$this->_file = $file;
	}
	
	/*****************************************************/
	
	/**
	 * Affichage de l'image
	 * @return void
	 */
	public function image() : void
	{
		$this->_response = new Response($this->_file->render());
		
		$mimeType = 'image/' . strtr($this->_file->extension, [ 'jpg' => 'jpeg', ]);
		
		$this->_response->addHeader('Content-Type', $mimeType);
	}
	
	/*****************************************************/
	
}