/**
 * Classe gérant des entiers
 */

class Integer  { 
	
	/**
	 * Retourne un entier entre les valeurs en paramètre
	 * @param int min
	 * @param int max
	 * @return int
	 */
	static random(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	};
	
}