<?php

/**
 * Classe gérant un modèle gérant le téléchargement d'image
 */

namespace App;

use Root\{ HTML, Image, FileUpload };

class ModelWithImageUpload extends Model {
	
	public const
		IMAGE_FORMAT_ORIGINAL = 'original',
		IMAGE_FORMAT_MEDIUM	= 'medium',
		IMAGE_FORMAT_SMALL = 'small'
	;
	
	/**
	 * Formats des images
	 * @var array
	 */
	protected static array $_images_formats = [];
	
	/***********************************************************/
	
	/**
	 * Retourne la liste des formats
	 * @return array
	 */
	public function imagesFormats() : array
	{
		return static::$_images_formats;
	}
	
	/***********************************************************/
	
	/**
	 * Retourne l'image sous forme de HTML
	 * @param string $field Champs en base de données
	 * @param string $version Version de l'image
	 * @param array $attributes Propriétés de la balise image
	 * @return string
	 */
	public function image(string $field, string $version = ImageResource::VERSION_MEDIUM, array $attributes = []) : ?string
	{
		$fileId = $this->{ $field };
		if($fileId === NULL)
		{
			return NULL;
		}
		
		$file = ImageResource::retrieve($fileId, $version);
		
		if($file === NULL)
		{
			return NULL;
		}
		
		$imageAttributes = array_replace([
			'alt' => '',
		], $attributes);
		
		return HTML::image($file->url(), $imageAttributes);
	}
	
	/***********************************************************/
	
	/**
	 * Déplace l'image téléchargé
	 * @param string $field Nom du champs correspondant à l'image
	 * @param array $fileData Les données du fichier téléchargé
	 * @return bool
	 */
	public function uploadImage(string $field, array $fileData) : bool
	{
		$formats = getArray($this->imagesFormats(), $field, []);
		if(count($formats) == 0)
		{
			return FALSE;
		}
		
		// Télécharge le fichier original
		$fileUpload = FileUpload::factory($fileData);
		$fileUpload->isImage(TRUE);
		
		// Récupération du chemin
		$path = $fileUpload->path();
		if(! $path)
		{
			return FALSE;
		}
		
		// Génération de l'image originale
		$originalImage = Image::factory($path);
		$originalImageResource = ImageResource::factory([
			'id' => $this->{ $field },
			'type' => ImageResource::TYPE_IMAGE,
			'content' => $originalImage->getContent(),
			'extension' => $originalImage->type(),
			'data' => json_encode($originalImage->getDimensions()),
			'version' => ImageResource::VERSION_ORIGINAL,
		]);
	
		// Enregistrement de l'image originale en base de données
		if(! $originalImageResource->save())
		{
			return FALSE;
		}
		
		$resourceChildren = $originalImageResource->children();
		
		// Création des diffèrents formats
		foreach($formats as $format => $dimensions)
		{
			// Génération de l'image au format demandé
			$image = Image::factory($path);
			$width = getArray($dimensions, 'width');
			$height = getArray($dimensions, 'height');
			$image->resize($width, $height);
			
			$resourceChild = getArray($resourceChildren, $format);
			
			// Génération du fichier ressource
			$imageResource = ImageResource::factory([
				'id' => ($resourceChild !== NULL) ? $resourceChild->id : NULL,
				'type' => ImageResource::TYPE_IMAGE,
				'parent_id' => $originalImageResource->id,
				'content' => $image->getContent(),
				'extension' => $image->type(),
				'data' => json_encode($image->getDimensions()),
				'version' => $format,
			]);
			
			// Enregistrement en base de données
			if(! $imageResource->save())
			{
				return FALSE;
			}
		}
		
		// Mise à jour du champs enrregistrement la référence
		if($this->{ $field } === NULL)
		{
			$this->{ $field } = $originalImageResource->id;
			$this->update();
		}
		
		return TRUE;
	}
	
	/***********************************************************/
	
}