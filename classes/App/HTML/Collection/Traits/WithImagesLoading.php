<?php

/**
 * Trait pour les collections devant charger des fichiers images en base de données
 */

namespace App\HTML\Collection\Traits;

use App\ModelWithImageUpload as Model;
use App\Collection\FileResourceCollection;

trait WithImagesLoading {
	
	/**
	 * Liste des images chargées par modèle
	 * @var array
	 */
	private $_images_by_model = NULL;
	
	final protected function _images(Model $model) : array
	{
		$formats = array_keys($model->imagesFormats());
		
		if($this->_images_by_model === NULL)
		{
			// Détermine les identifiants des images à charger
			$imageIds = [];
			$collection = $this->_collection();
			foreach($collection as $currentModel)
			{
				foreach($formats as $field)
				{
					$fieldValue = $currentModel->{ $field };
					if($fieldValue !== NULL AND ! in_array($fieldValue, $imageIds))
					{
						$imageIds[] = $fieldValue;
					}
				}
				
			}
			
			$images = [];
			
			if(count($imageIds) > 0)
			{
				// Regroupe les images par identifiant de parent
				$imagesById = [];
				$imageCollection = FileResourceCollection::factory()->withIdsOrParentIds($imageIds)->get();
				foreach($imageCollection as $image)
				{
					$key = ($image->parent_id) ?? $image->id;
					if(! getArray($imagesById, $key))
					{
						$imagesById[$key] = [];
					}
					$imagesById[$key][$image->version] = $image;
				}
				
				// Regroupe les images par model
				foreach($collection as $currentModel)
				{
					$modelId = $currentModel->identifier();
					foreach($formats as $field)
					{
						$fieldValue = $currentModel->{ $field };
						if($fieldValue !== NULL)
						{
							if(! array_key_exists($modelId, $images))
							{
								$images[$modelId] = [];
							}
							$images[$modelId][$field] = getArray($imagesById, $fieldValue);
						}
					}
				}
			}
			
			$this->_images_by_model = $images;
		}
		return getArray($this->_images_by_model, $model->identifier(), []);
	}
	
}