<?php

/**
 * Tâche permettant de créer une archive de la démonstration
 * php cli demo-archive
 */

namespace App\Tasks;

use Root\{ Task, Directory };
use ZipArchive;

class DemoArchiveTask extends Task {
	
	/**
	 * Identifiant de la tâche
	 * @var string
	 */
	protected string $_identifier = 'demo-archive';
	
	/**
	 * Archive
	 * @var ZipArchive
	 */
	private ZipArchive $_archive;
	
	/**
	 * Répertoires à exclure
	 * @var array
	 */
	private static array $_excluded_directories = [
		'resources/sass',
		'resources/scripts/files',
	];
	
	/**
	 * Fichiers à exclure
	 * @var array
	 */
	private static array $_excluded_files = [
		'config/environments/production/database.php',
		'config/environments/testing/database.php',
		'database/initialize.sql',
	];
	
	/*******************************************************/
	
	/**
	 * Exécute la tâche
	 * @return void
	 */
	protected function _execute() : void
	{
		$this->_archive = new ZipArchive;
		
		$archiveDirectory = strtr('../../resources/:environment/:site/', [
			':site' => getConfig('site.directory'),
			':environment' => strtolower(environment()),
		]);
		
		if(! Directory::create($archiveDirectory))
		{
			exception('Impossible de créer le répertoire.');
		}
		
		$archiveFilepath = $archiveDirectory . 'demo.zip';
		
		
		if(! @ $this->_archive->open($archiveFilepath, ZipArchive::CREATE)) 
		{
			exception('Impossible de créer l\'archive.');	
		}
		
		// Création des fichiers à la racine
		$files = [ 'cli', 'index.php', '.htaccess', ];
		foreach($files as $file)
		{
			$this->_archive->addFile($file, $file);
		}
		
		// Création des répertoires
		$directories = [ 'classes', 'config', 'database', 'resources', 'routes', 'translations', 'views', ];
		foreach($directories as $directory)
		{
			$this->_addFileDirectory($directory);
		}
		
		$this->_archive->close();
	}
	
	/**
	 * Ajoute le contenu d'un répertoire à l'archive
	 * @param string $directory
	 * @return void
	 */
	private function _addFileDirectory(string $directory) : void
	{
		$excludedDirectories = self::$_excluded_directories;
		$excludedFiles = self::$_excluded_files;
		
		@ $directoryResource = opendir($directory);
		if(! $directoryResource)
		{
			exception('Répertoire inconnu.');
		}
		
		while($fileDirectory = readdir($directoryResource))
		{
			$path = $directory . '/' . $fileDirectory;
			
			if(! in_array($path, $excludedFiles) AND is_file($path) AND $fileDirectory != '.gitignore')
			{
				$this->_archive->addFile($path);
			}
			elseif($fileDirectory != '.' AND $fileDirectory != '..' AND ! in_array($path, $excludedDirectories) AND is_dir($path))
			{
				$this->_archive->addEmptyDir($path);
				$this->_addFileDirectory($path);
			}
		}
		
		closedir($directoryResource);
	}
	
}