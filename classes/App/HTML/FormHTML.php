<?php

/**
 * Gestion du HTML des formulaires
 */ 

namespace App\HTML;

use Root\HTML\FormHTML as FormRootHTML;
use Root\{ HTML };

class FormHTML extends FormRootHTML {
	
	/**
	 * Retourne le HTML d'un champs d'autocomplètion
	 * @param string $fieldName Nom du champs
	 * @param string $fieldValue Valeur du champs
	 * @param array $options : array(
	 * 		'request_url' => <string>, 	// URL de la requête
	 * 		'text_value' => <string>, 	// Valeur du champs affichée
	 * 		'text_id' => <string>,		// Identifiant du champs texte
	 * 		'placeholder' => <string>, 	// Texte affiché dans le champs par défaut
	 * 		'model' => <string>,		// Classe du modèle à utiliser 
	 * )
	 * 
	 * 
	 * string $requestURL URL de la requête
	 * @return string
	 */
	public static function inputAutocomplete(string $fieldName, ?string $fieldValue, array $options) : string
	{
		$requestURL = getArray($options, 'request_url');
		$fieldText = getArray($options, 'text_value');
		$fieldTextId = getArray($options, 'text_id');
		$modelClass = getArray($options, 'model');
		
		if(! class_exists($modelClass))
		{
			exception('La classe du modèle n\'existe pas.');
		}
		
		if($requestURL === NULL)
		{
			exception('URL de la requête inconnue.');
		}
		
		$inputTextAttributes = [
			'autocomplete' => 'off',
			'class' => 'autocomplete-text',
			'placeholder' => getArray($options, 'placeholder'),
			'data-model' => $modelClass,
			'id' => $fieldTextId,
		];
		
		$inputValueAttributes = [
			'class' => 'autocomplete-value',
		];
		
		// Champs inputs nécessaires
		$inputs = [
			// Champs de recherche
			self::text($fieldName . '-text', $fieldText, $inputTextAttributes),
			// Champs stockant l'identifiant
			self::hidden($fieldName, $fieldValue, $inputValueAttributes),
		];
		
		$attributes = HTML::attributes([
			'class' => 'autocomplete-search',
			'data-url' => $requestURL,
		]);
		
		$content = strtr('<div :attributes>:inputs</div>', [
			':attributes' => $attributes,
			':inputs' => implode("", $inputs),
		]);
		
		return $content;
	}
	
}
