<?php

/**
 * Gestion d'un pays
 */

namespace App;

use App\Traits\Models\{ WithFlag };

class Country extends ModelWithImageUpload {
	
	use WithFlag;
	
	/**
	 * Table du modèle
	 * @var string
	 */
	public static string $table = 'countries';
	
	/**
	 * Clé primaire
	 * @var string
	 */
	protected static string $_primary_key = 'code';
	
	/**
	 * Vrai si la clé primaire est un auto-incrément
	 * @var bool
	 */
	protected static bool $_autoincrement = FALSE;
	
	/*****************************************************/
	/**
	 * Formats des images
	 * @var array
	 */
	protected static array $_images_formats = [
		'image' => [
			ImageResource::VERSION_SMALL => [
				'width'	=> 100,
				'height' => 50,
			],
		],
	];
	
	/*****************************************************/
	
}