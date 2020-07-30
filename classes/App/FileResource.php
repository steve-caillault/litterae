<?php

/**
 * Gestion d'un fichier enregistré en base de données
 */

namespace App;

use App\Collection\FileResourceCollection;

abstract class FileResource extends Model {
    
    public const TYPE_IMAGE = 'IMAGE';
    
    /**
     * Base de données à utiliser
     * @var string
     */
    public static string $database = 'resources';
    
    /**
     * Table du modèle
     * @var string
     */
    public static string $table = 'file_resources';
    
    /*******************************************************/
    
    /**
     * Identifiant en base de données
     * @var int
     */
    public ?int $id = NULL;
    
    /**
     * Identifiant du fichier parent
     * @var int
     */
    public ?int $parent_id = NULL;
    
    /**
     * Identifiant public
     * @var string
     */
    public ?string $public_id;
    
    /**
     * Type de fichier
     * @var string
     */
    public string $type;
    
    /**
     * Contenu du fichier
     * @var string
     */
    public string $content;
    
    /**
     * Extension du fichier
     * @var string
     */
    public string $extension;
    
    /**
     * Version du fichier
     * @var string 
     */
    public string $version;
    
    /**
     * Données supplémentaires (pour une images, la taille de l'image)
     * Chaine JSON
     * @var string
     */
    public string $data = '{}';
    
    /*******************************************************/
    
    /**
     * Fichiers enfants
     * @var array
     */
    private ?array $_children = NULL;
    
    /**
     * Fichiers déjà chargés
     * @var array
     */
    private static array $_files_loaded = [];
    
    /*******************************************************/
    
    /**
     * Méthode de construction statique à partir des paramètres nécessaire pour l'instanciation
     * @param array $params Paramètre de l'objet
     * @return self
     */
    public static function _construct(array $params) : Model
    {
    	$type = getArray($params, 'type');
    	$class = __NAMESPACE__ . '\\' . ucfirst(strtolower($type)) . 'Resource';
    	return new $class($params);
    }
    
    /*******************************************************/
    
    /**
     * Retourne les fichiers enfants
     * @return array
     */
    public function children() : array
    {
    	if($this->_children === NULL)
    	{
    		$files = [];
    		$collection = FileResourceCollection::factory()->withParent($this)->get();
    		foreach($collection as $file)
    		{
    			$files[$file->version] = $file;
    		}
    		$this->_children = $files;
    	}
    	return $this->_children;
    }
    
    /**
     * Retourne l'image dont l'identifiant de l'image original et la version sont en paramètres
     * @param int $originalId Identifiant de l'image original
     * @param string $version 
     * @return self
     */
    public static function retrieve(int $originalId, string $version) : ?self
    {
    	if(! array_key_exists($originalId, self::$_files_loaded))
    	{
    		self::$_files_loaded[$originalId] = [];
    		$collection = FileResourceCollection::factory()->withIdsOrParentIds([ $originalId ])->get();
    		foreach($collection as $image)
    		{
    			self::$_files_loaded[$originalId][$image->version] = $image;
    		}
    	}
    	$images = getArray(self::$_files_loaded, $originalId);
    	return getArray($images, $version);
    }
    
    /**
     * Retourne le fichier en faisant une recherche par identifiant public
     * @param string $publicId
     * @return self
     */
    public static function findByPublicId(string $publicId) : ?self
    {
    	return static::searchWithCriterias([
    		[
    			'left' => 'public_id',
    			'right' => $publicId,
    		],
    	]);
    }
    
    /*******************************************************/
    
    /**
     * Retourne l'URL du fichier
     * @return string
     */
    public function url() : string
    {
    	return strtr('data:type/extension;base64,content', [
    		'type' => strtolower($this->type),
    		'extension' => $this->extension,
    		'content' => base64_encode($this->content),
    	]);
    }
    
    /**
     * Retourne le rendu du fichier
     * @return string
     */
    abstract public function render() : string;

    /*******************************************************/
    
	/**
     * Sauvegarde de l'objet en base de donn�es
     * @return bool
     */
    public function save() : bool
    {
    	$key = ($this->id ?? rand()) . time() . rand();
    	$this->public_id = hash('crc32', $key) . '.' . $this->extension;
    	
    	return parent::save();
    }
    
    /*******************************************************/
    
}