<?php

/**
 * Gestion des nombres
 * @author Stève Caillault
 */

namespace App;

class Number {
	
	/**
	 * Retourne la chaine de caractère du nombre en paramètre formatée pour l'affichage
	 * @param float $value
	 * @param int $decimal Nombre de décimal
	 * @return string
	 */
	public static function format(float $value, int $decimal = 0) : string
	{
		return number_format($value, $decimal, ',', ' ');
	}
	
}