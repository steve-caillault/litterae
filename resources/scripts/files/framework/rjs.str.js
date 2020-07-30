/**
 * Classe gérant des chaines de caractères
 */

class Str {
	
	/**
	 * Retourne une chaine en remplaçant les clés du tableau replacePairs par ses valeurs dans la chaine de caractère 
	 * Il s'agit d'un équivalent de la fonction PHP strtr (http://php.net/manual/fr/function.strtr.php)
	 * @param string string Chaine à modifier
	 * @param object from Tableau des correspondances
	 * @return string
	 */
	static replace(string, replacePairs) {
		for(var key in replacePairs) {
			var value = replacePairs[key];
			string = string.replace(key, value);
		}
		return string;
	};
	
	/**
	 * Retourne un identifiant unique
	 * @return string
	 */
	static uniqueId() {
		
		var intervalsByType = {
				'letter': { 'min': 97, 'max': 122 },
				'integer': { 'min': 48, 'max': 57 }
			},
			characterTypes = Object.keys(intervalsByType),
			iteration = Integer.random(5, 10),
			identifiers = [],
			numberCharacters, characterType, intervals, i, j
		;
		
		for(i = 0 ; i < iteration ; i++) {
			numberCharacters = Integer.random(10, 20);
			for(j = 0 ; j < numberCharacters ; j++) {
				characterType = characterTypes[Integer.random(0, characterTypes.length - 1)];
				
				intervals = intervalsByType[characterType];
				if(! identifiers[i]) {
					identifiers[i] = '';
				}
				identifiers[i] += String.fromCharCode(Integer.random(intervals['min'], intervals['max']));
			}
		}
		
		return identifiers.join('');
	};
	
}