<?php

/**
 * Gestion d'une image enregistrée en base de données
 */

namespace App;

use Root\{ Route, Image, URL };

class ImageResource extends FileResource {
    
    public const 
    	VERSION_ORIGINAL = 'ORIGINAL',
    	VERSION_MEDIUM = 'MEDIUM',
    	VERSION_SMALL = 'SMALL'
    ;
    
    /**
     * Type de fichier
     * @var string
     */
    public string $type = self::TYPE_IMAGE;
   
    /*******************************************************/
    
    /**
     * Retourne l'URL du fichier
     * @return string
     */
    public function url() : string
    {
    	$uri = Route::retrieve('resources.image')->uri([
    		'file' => $this->public_id,
    	]);
    	
    	return URL::get($uri);
    }
    
    /**
     * Retourne le rendu du fichier
     * @return string
     */
    public function render() : string
    {
    	$image = Image::factory(parent::url());
    	return $image->getContent();
    }
    
    /*******************************************************/
    
}