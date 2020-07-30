<?php

/**
 * Contrôleur de base du site
 * @author Stève Caillault
 */

namespace App\Controllers\HTML;

use App\Site;
use Root\Controllers\HTMLController as HTMLController;
use App\Site\Meta;
use Root\HTML;

abstract class BodyController extends HTMLController
{
    /**
     * Vue de base à utiliser
     * @var string
     */
    protected string $_templatePath = 'html/body';
   
    /**
     * Titre de la balise title
     * @var string
     */
    protected ?string $_head_title = NULL;
    
    /**
     * Objet permetant de gérer une liste de balise meta
     * @var Meta
     */
    protected Meta $_site_meta;
    
    /**
     * Description de la balise meta description
     * @var string
     */
    protected ?string $_meta_description = NULL;
    
    /**
     * Tableau des mots clé de la balise meta keywords
     * @var array
     */
    protected array $_meta_keywords	= [];
    
    /**
     * Contenu principal de la page
     * @var string
     */
    protected string $_content;
    
    /**
     * Vrai si on charge les fichiers JavaScript
     * @var bool
     */
    protected bool $_active_javascript = FALSE;
    
    /****************************************************************************************************************************/
    
    public function before() : void
    {
        parent::before();
        // Initialisation des balises metas
        $this->_site_meta = Meta::factory([
            'name' => 'robots',
            'content' => 'noindex,nofollow',
        ])->set([
            'charset' => 'utf-8',
        ]);
    }
    
    public function after() : void
    {
        // Balise meta description
        $metaDescription = Site::description($this->_meta_description);
        if($metaDescription !== NULL AND $metaDescription != $this->_head_title)
        {
            $this->_site_meta->set([
                'name'		=> 'description',
                'content'	=> $metaDescription,
            ]);
        }
        
        // Balise meta keywords
        $this->_site_meta->set([
            'name'			=> 'keywords',
            'content'		=> Site::keywords($this->_meta_keywords),
    	]);
        
        // Balise meta viewport
        $this->_site_meta->set([
        	'name' => 'viewport',
        	'content' => 'width=device-width,initial-scale=1.0',
        ]);
        
        $siteMeta = $this->_site_meta->render();
        // Titres
        $headTitle = Site::title($this->_head_title);
        // Contenu
        $content = $this->_content;
        // Scripts Javascript
        $scripts = Site::scripts($this->_active_javascript);
        // Styles CSS
        $styles = Site::styles();
        // Icône favori
        $favoriteIcon = NULL;
        $favoriteIconUrl = getConfig('static.icons.favorite');
        if($favoriteIconUrl !== NULL)
        {
	        $favoriteIcon = HTML::link('resources/images/favicon.ico', [
	            'rel'	=> 'shortcut icon',
	        ]);
        }
        
        // On transmet les variables à la vue
        $this->_template->setVars([
            'headTitle'   	 	=> $headTitle,
            'metas'         	=> $siteMeta,
            'styles'       		=> $styles,
            'scripts'       	=> $scripts,
        	'favoriteIcon'      => $favoriteIcon,
            'content'       	=> $content,
        ]);
        
        // Pour enregistrer les requêtes dans un fichier
        /*$queries = \Root\DB::queries();
        debug($queries, TRUE);
        if(count($queries) > 0)
        {
        	logMessage(implode(PHP_EOL, $queries));
        }*/
        
        parent::after();
    }
}