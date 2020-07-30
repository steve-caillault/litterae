<?php

/**
 * Tâches permettant de versionner les fichiers statique
 * Le script devrait être appelé àla fin du script de déploiement
 */

namespace App\Tasks;

use Root\{ Task, File, Config };
/***/
use App\Site;

class StaticFilesVersion extends Task {
	
	/**
	 * Identifiant de la tâche
	 * @var string
	 */
	protected string $_identifier = 'static-files-version';
	
	/**
	 * Données du fichiers de configuration
	 * @var array
	 */
	private array $_static_config;
	
	/*************************************************/
	
	/**
	 * Exécute la tâche
	 * @return void
	 */
	protected function _execute() : void
	{
		$this->_static_config = getConfig('static');
		
		$this->_manageStyles();
		$this->_manageScripts();
		
		Config::updateFile('static', $this->_static_config);
	}
	
	/*************************************************/
	
	/**
	 * Gestion des fichiers des styles
	 * @return void
	 */
	private function _manageStyles() : void
	{
		$config = getArray($this->_static_config, 'styles', []);
		$fileGroups = getArray($config, 'files');
		$this->_manageFileGroups($fileGroups, Site::DIRECTORY_STYLES);
		$this->_static_config['styles']['files'] = $fileGroups;
	}
	
	/**
	 * Gestion des fichiers des scripts
	 * @return void
	 */
	private function _manageScripts() : void
	{
		$config = getArray($this->_static_config, 'scripts', []);
		$fileGroups = [
			'files' => $config,
		];
		$this->_manageFileGroups($fileGroups, Site::DIRECTORY_SCRIPTS);
		$this->_static_config['scripts'] = getArray($fileGroups, 'files', []);
	}
	
	/**
	 * Gestion d'un groupe de fichiers
	 * @param array $fileGroups Le groupe de fichier à gérer
	 * @param string $directory Répertoire où se trouvent les fichiers
	 * @return void
	 */
	private function _manageFileGroups(array &$fileGroups, string $directory) : void
	{
		foreach($fileGroups as $group => $files)
		{
			foreach($files as $index => $originalFile)
			{
				$originalPath = $directory . $originalFile;
				$versionName = $this->_getVersionName($originalPath);
				if($versionName === NULL)
				{
					continue;
				}
				$fileGroups[$group][$index] = $versionName;
			}
		}
	}
	
	/*************************************************/
	
	/**
	 * Retourne le nom du fichier versionné
	 * @param string $originalPath Chemin du fichier original
	 * @return string
	 */
	private function _getVersionName(string $originalPath) : ?string
	{
		$content = @ file_get_contents($originalPath);
		if(! $content)
		{
			return NULL;
		}
		
		$key = hash('crc32', $content);
		
		$originalFileName = basename($originalPath);
		$originalName = File::name($originalFileName); 
		
		$versionName = strtr($originalFileName, [
			$originalName => $originalName . '.' . $key,
		]); 
		
		$versionPath = strtr($originalPath, [
			$originalFileName => $versionName,
		]); 
		
		// Ecrit les données dans le fichier
		$wrote = @ file_put_contents($versionPath, $content);
		if(! $wrote)
		{
			return NULL;
		}
		
		return $versionName;
	}
	
	/*************************************************/
	
}