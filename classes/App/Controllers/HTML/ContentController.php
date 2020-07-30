<?php

/**
 * Contrôleur de contenu d'une page HTML de base
 * @author Stève Caillault
 */

namespace App\Controllers\HTML;

use App\Site\Breadcrumb;
use App\HTML\Menu\{ HeaderHTML, MenuHTML };
/***/
use Root\{ View, HTML };

abstract class ContentController extends BodyController
{
    /**
     * Titre de la page (pour affichage)
     * @var string
     */
    protected ?string $_page_title = NULL;
    
    /**
     * Object permettant de gérer un fil d'ariane
     * @var Breadcrumb
     */
    protected Breadcrumb $_site_breadcrumb;
    
    /**
     * Liste des menus à afficher
     * @var array
     */
    protected array $_menus = [
    	MenuHTML::TYPE_SECONDARY => NULL,
    	MenuHTML::TYPE_TERTIARY => NULL,
	];
    
    /**
     * Contenu principal de la page
     * @var string
     */
    protected string $_main_content;
    
    /****************************************************************/
    
    /**
     * Gestion du fil d'ariane
     * @return void 
     */
    protected function _manageBreadcrumb() : void
    {
    	$this->_site_breadcrumb = Breadcrumb::instance();
    }
    
    /**
     * Gestion du titre de la page
     * @return void
     */
    protected function _managePageTitle() : void
    {
    	// Rien par défaut    
    }
    
    /****************************************************************/
    
    public function after() : void
    {
    	$this->_managePageTitle();
		$this->_manageBreadcrumb();
    	
        // Menus
        $menus = NULL;
        if($this->_menus[MenuHTML::TYPE_SECONDARY] !== NULL OR $this->_menus[MenuHTML::TYPE_TERTIARY] !== NULL)
        {
            $menus = View::factory('site/menu/submenu', [
            	'menus' => $this->_menus,
            ]);
        }
     
        // Contenu principal
        $mainContent = View::factory('html/content/main', [
        	'breadcrumb' => $this->_site_breadcrumb->render(),
        	'pageTitle' => $this->_page_title,
        	'content' => $this->_main_content,
        ]);
        
        $content = $mainContent;
        
        $attributes = [
        	'id' => 'main-content',
        	'class' => ($menus === NULL) ? 'one-column' : 'two-columns',
        ];
        
        // Deux colonnes
        if($menus !== NULL)
        {
        	$content = View::factory('html/content/two-columns', [
        		'menus' => $menus,
        		'content' => $mainContent,
        	]);
        }
        
        $this->_content = View::factory('html/content', [
        	'header' => HeaderHTML::render(),
        	'content' => $content,
        	'attributes' => HTML::attributes($attributes),
        ]);
     
        parent::after();
    }
    
    /****************************************************************/
}
