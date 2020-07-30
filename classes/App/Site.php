<?php

/**
 * Classe utilitaire pour le site
 */

namespace App;

use Root\{ HTML, Environment };

class Site {
    
	public const 
		TYPE_ADMIN = 'ADMIN',
		TYPE_SITE = 'SITE',
		/***/
		DIRECTORY_STYLES = 'resources/styles/',
		DIRECTORY_SCRIPTS = 'resources/scripts/'
	;
	
	/**
	 * Type de site
	 * @var string
	 */
	private static string $_type = self::TYPE_SITE;
	
    /**
     * Nom du site
     * @var string
     */
    private static ?string $_name = NULL;
    
    /**
     * Titre de la page
     * @var string
     */
    private static ?string $_title = NULL;
    
    /**
     * Description de la page
     * @var string 
     */
    private static ?string $_description = NULL;
    
    /********************************************************************************/
    
    /**
     * Retourne, modifit le type de site
     * @param string $type Si renseigné, le type de site à affecter
     * @return string
     */
    public static function type(?string $type = NULL) : string
    {
    	if($type !== NULL)
    	{
    		self::$_type = $type;
    	}
    	return self::$_type;
    }
    
    /********************************************************************************/
    
    /* BALISE TITLE / META */
    
    /**
     * Retourne le nom du site
     * @return string
     */
    public static function name() : ?string
    {
        if(self::$_name === NULL)
        {
            self::$_name = getConfig('site.name');
        }
        return self::$_name;
    }
    
    /**
     * Retourne le nom de la page actuelle
     * @param string $title Si renseigné, le titre de la page
     * @return string
     */
    public static function title($title = NULL) : ?string
    {
        if(self::$_title === NULL)
        {
            if($title === NULL)
            {
                $title = self::name();
            }
            self::$_title = $title;
        }
        return self::$_title;
    }
    
    /**
     * Retourne la description du site
     * @param string $description Si renseigné, la description à affecter 
     * @return string
     */
    public static function description(?string $description = NULL) : ?string
    {
        if(self::$_description === NULL)
        {
            if($description === NULL)
            {
            	$description = getConfig('site.description');
            }
            self::$_description = $description;
        }
        return self::$_description;
    }
    
    /**
     * Retourne les mots clés du site, si on en transmet en paramètres on fusionne un tableau
     * @param array $keywords Tableau des mots clés de la page actuelle
     * @return array
     */
    public static function keywords(array $keywords = []) : string
    {
        if($siteName = static::name())
        {
            $keywords[] = $siteName;
        }
        return implode(', ', $keywords);
    }
    
    /********************************************************************************/
    
    /* BALISES SCRIPTS */
    
    /**
     * Retourne les balises JavaScripts su site
     * @param bool $actived Vrai si le JavaScript est actif
     * @return string
     */
    public static function scripts($actived = FALSE) : ?string
    {
    	if(! $actived)
    	{
    		return NULL;
    	}
    	
    	$isDevelopment = (environment() == Environment::DEVELOPMENT);
    	
    	$scripts = '';
    	
    	if($configScripts = getConfig('static.scripts'))
    	{
    		foreach($configScripts as $script)
    		{
    			$url = self::DIRECTORY_SCRIPTS . $script;
    			if($isDevelopment)
    			{
    				$url .= '?v=' . time();
    			}
    			$scripts .= HTML::script($url);
    		}
    	}
    	return $scripts;
    }
    
    /****************************************************************************************************************************/
    
    /* BALISES STYLES */
    
    /**
     * Retourne les balises de styles du site
     * @return string
     */
    public static function styles() : string
    {
    	$styles = '';
    	$configStyles = getConfig('static.styles.files');
    	
    	$siteFiles = getArray($configStyles, strtolower(self::type()), []);
    	
    	//debug($siteFiles, TRUE);
    	
    	$isDevelopment = (environment() == Environment::DEVELOPMENT);
    	
    	foreach($siteFiles as $style)
    	{
    		$url = self::DIRECTORY_STYLES . $style;
    		if($isDevelopment)
    		{
    			$url .= '?v=' . time();
    		}
    		$styles .= HTML::style($url);
    	}
    	
    	// Style pour l'impression
    	$printFileURL = getArray($configStyles, 'print');
    	if($printFileURL !== NULL)
    	{
    		$styles .= HTML::style($printFileURL, [
    			'media' => 'print',
    		]);
    	}
    	
    	return $styles;
    }
    
    /****************************************************************************************************************************/
    
    
}