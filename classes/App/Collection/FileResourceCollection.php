<?php

/**
 * Gestion d'une liste de fichier stockés en base de données
 */

namespace App\Collection;

use App\FileResource;

class FileResourceCollection extends Collection {
	
	/**
	 * Classe du modèle à utiliser pour la récupération de la table, des colonnes et de l'instanciation des objets
	 * @var string
	 */
	protected ?string $_model_class = FileResource::class;
	
	/**
	 * La base de données sur laquelle éxécuter la requête
	 * @var string
	 */
	protected string $_database = 'resources';
	
	/**************************************************************/
	
	/**
	 * Filtre les fichiers sans identifiant public
	 * @return self
	 */
	public function withoutPublicId() : self
	{
		$this->_query->where($this->_table . '.public_id', '=', NULL);
		return $this;
	}
	
	/**
	 * Filtre les enfants du fichier en paramètre
	 * @param FileResource $file
	 * @return self
	 */
	public function withParent(FileResource $file) : self
	{
		$this->_query->where($this->_table . '.parent_id', '=', $file->id);
		return $this;
	}
	
	/**
	 * Filtre les identifiants ou les parents
	 * @param array $ids
	 * @return self
	 */
	public function withIdsOrParentIds(array $ids) : self
	{
		$fields = [ 'id', 'parent_id', ];
		
		foreach($fields as $field)
		{
			$this->_query->orWhere($this->_table . '.' . $field, 'IN', $ids);
		}
		
		return $this;
	}
	
	/**************************************************************/
	
}