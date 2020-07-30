<?php

/**
 * Gestion d'une collection d'un éditeur
 */

namespace App;

class Collection extends Editor {
	
	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'collections';
	
	/**
	 * Classe de l'objet éditeur à utiliser
	 */
	protected static string $_editor_class = Editor::class;
	
	/*********************************************************************/
	
	/* CHAMPS EN BASE DE DONNEES */
	
	/**
	 * Identifiant de l'éditeur en base de données
	 * @var $int
	 */
	public ?int $editor = NULL;
	
	/*********************************************************************/
	
	/**
	 * Editeur de la collection
	 * @var Editor
	 */
	private ?Editor $_editor = NULL;
	
	/*********************************************************************/
	
	/* GET */
	
	/**
	 * Retourne l'éditeur
	 * @return Editor
	 */
	public function editor() : Editor
	{
		if($this->_editor === NULL)
		{
			$this->_editor = static::$_editor_class::factory($this->editor);
		}
		return $this->_editor;
	}
	
	/*********************************************************************/
	
}