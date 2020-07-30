<?php

/**
 * Gestion d'un fil d'Ariane
 */

namespace App\Site;

use Root\{ Instanciable, Route, View, HTML };

class Breadcrumb extends Instanciable {
	
	/**
	 * Eléments du fil d'Ariane
	 * @var array
	 */
	private array $_items = [];
	
	/********************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
     * Initialisation de l'instance unique
     * @return self
     */
	protected static function _initInstance() : self
	{
		$breadcrumb = new static;
		$breadcrumb->add([
			'href'	=> Route::retrieve('home')->uri(),
			'name'	=> 'Accueil',
			'alt'	=> 'Revenir à la page d\'accueil.',
		]);
		return $breadcrumb;
	}
	
	/********************************************/
	
	/**
	 * Ajoute un élément au fil d'ariane
	 * @param array $params
	 * @return self
	 */
	public function add(array $params) : self
	{
		$uri = getArray($params, 'href');
		
		$title = $params['name'];
	
		if($uri === NULL)
		{
			$item = $title;
		}
		else
		{
			$item = HTML::anchor($uri, $title, [
				'title' => getArray($params, 'alt'),
			]);
		}
		
		
		
		$this->_items[] = $item; 
		
		return $this;
	}
	
	/********************************************/
	
	/**
	 * Retourne le HTML du fil d'ariane
	 * @return View
	 */
	public function render() : ?View
	{
		if(count($this->_items) <= 1)
		{
			return NULL;
		}
		
		// On retire l'ancre pour ne garder que le texte du dernier élément
		$lastIndex = count($this->_items) - 1;
		$this->_items[$lastIndex] = strip_tags($this->_items[$lastIndex]);

		$content = View::factory('tools/breadcrumb', [
			'items' => $this->_items,
		]);
		return $content;
	}
	
	/**
	 * Méthode d'affichage
	 * @return View
	 */
	public function __toString() : string
	{
		return ((string) $this->render());
	}
	
	/********************************************/
	
}

