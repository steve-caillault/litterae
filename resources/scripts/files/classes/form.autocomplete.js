class FormAutocomplete extends Component {
	
	// inputs: {"text" : null, "hidden": null} // Liste des champs nécessaires
	// choices: null // Element HTML avec la liste des suggestions
	
	constructor(component, ready = false) {
		super(component, ready);
		
		this.component.addClass('init');
		this.choices = null;
		this.inputs = {
			"search": ElementHTML.searchOne('input.autocomplete-text', this.component.htmlElement),
			"value": ElementHTML.searchOne('input.autocomplete-value', this.component.htmlElement)
		};
		
		this.onReady();
	};
	
	initEvents() {
		var self = this,
			inputSearch = this.inputs['search']
		;
		
		// Lorsqu'un caractère est ajouté ou supprimé
		inputSearch.addEvent('input', function(event) {
			var value = this.value,
				length = value.length
			;
			
			if(length == 0) { // Si on vide le champs on vide l'identifiant pré-sélectionné
				self.inputs['value'].setProperty('value', '');
				// self.component.fireEvent('updateValue');
			} else if(length > 2 && ! self._loading) { // Sinon on effectut la recherche
				self.request(value);
			}
		});
		
		// Navigation
		inputSearch.addEvent('keyup', function(event) {
			var key = event.key,
				navigationKeys = [ 'ArrowUp', 'ArrowDown' ],
				isNavigationKey = (navigationKeys.indexOf(key) != -1),
				length = ElementHTML.retrieve(this).getProperty('value').length
			;
			
			if(key == 'ArrowRight') { // Touche droite : sélection du choix
				var choice = (self.choices != null) ? ElementHTML.searchOne('li.target', self.choices.htmlElement) : null;
				if(choice != null) {
					choice.fireEvent('select');
				}
			} else if(isNavigationKey) { // Gestion de la navigation avec les flèches haut et bas
				if(self.choices != null) {
					self.navigation(key);
				}
			}
		});
		
		// Lorsqu'on perd le flux
		inputSearch.addEvent('blur', function(event) {
			if(self.choices != null) {
				self.choices.addClass('hide');
				setTimeout(function() {
					self.deleteChoices();
				}, 500);
			}
			
		});
		
	};
	
	showLoading() {
		this.component.addClass('loading');
	};
	
	hideLoading() {
		this.component.removeClass('loading');
	};
	
	/**
	 * Requête Ajax et affichage des résultats
	 * @return void
	 */
	request() {
		
		var self = this;
		
		this.loading(true);

		this.deleteChoices();
		
		
		new JSONAjaxRequest({
			"url": this.component.getProperty('data-url'),
			"method": "post",
			"params": {
				"value": this.inputs['search'].getProperty('value'),
				"model": this.inputs['search'].getProperty('data-model')
			},
			"onComplete": function() {
				self.loading(false);
			},
			"onSuccess": function(response) {
				if(response.length > 0) {
					var choices = new ElementHTML('ul', {
						'class': 'autocomplete-choices hide',
					});
					response.forEach(function(choiceData) {
						var choice = new ElementHTML('li', {
							'class': 'autocomplete-choice',
							'data-value': choiceData.value,
							'text': choiceData.text,
						});
						self.initChoice(choice);
						choices.addElement(choice);
						
					});
					self.choices = choices;
					self.component.addElement(choices);
					setTimeout(function() {
						choices.removeClass('hide');
					}, 500);
					
				}
			}
		});
		
	};
	
	/**
	 * Initialisation du choix en paramètre
	 * @param ElementHTML choice
	 * @return void
	 */
	initChoice(choice) {
		
		var self = this;
		// A la sélection d'un choix
		choice.addEvent('select', function(event) {
			var element = ElementHTML.retrieve(this),
				value = element.getProperty('data-value'),
				text = element.getProperty('text')
			;
			self.inputs['value'].setProperty('value', value);
			self.inputs['search'].setProperty('value', text);
			// self.component.fireEvent('selectElement', [ choice ]);
			// self.component.fireEvent('updateValue');
			self.choices.addClass('hide');
			setTimeout(function() {
				self.deleteChoices();
			}, 500);
		});
		
		// Clic sur le choix
		choice.addEvent('click', function() {
			var element = ElementHTML.retrieve(this);
			// console.log(Events.listByElement);
			//console.log(element.events('select'));
			choice.fireEvent('select');
		});
		
		// Survole du choix
		choice.addEvent('mouseover', function() {
		
			var element = ElementHTML.retrieve(this);
			
			var choices = ElementHTML.searchList('li', element.getParent('ul').htmlElement);
			choices.forEach(function(el) {
				el.removeClass('target');
			});
			
			element.addClass('target');
		});
	};
	
	/**
	 * Suppression des choix
	 * @return void
	 */
	deleteChoices() {
		
		var choices = this.choices;
		//console.log(choices);
		
		if(choices) {
			this.choices = null;
			choices.remove();
		}
	};
	
	/**
	 * Gestion de la navigation
	 */
	navigation(key) {

		var choices = ElementHTML.searchList('li', this.choices.htmlElement),
			currentTarget = ElementHTML.searchOne('li.target', this.choices.htmlElement),
			nextTarget = null
		;
			
		// Il n'y a pas assez d'éléments pour naviguer
		if(choices.length < 1) {
			return;
		}

		if(currentTarget != null) {
			currentTarget.removeClass('target');
			if(key == 'ArrowUp') {
				nextTarget = currentTarget.getPrevious('li');
			} else {
				nextTarget = currentTarget.getNext('li');
			}
		} 
		
		if(nextTarget == null) {
			if(key == 'ArrowUp') { // On met le dernier élément en subrillance
				nextTarget = this.choices.getLast('li');
			} else { // On met le premier élément en subrillance
				nextTarget = this.choices.getChild('li');
			}
		}
		
		nextTarget.addClass('target');
	};
}