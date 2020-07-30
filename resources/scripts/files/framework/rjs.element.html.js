/**
 * Gestions des événements
 */
Events = {
	listByElement: {},
	
	add: function(element, type, callback) {
		
		var eventsByType = element.events(type),
			events = element.events()
		;
		
		eventsByType.push(callback);
		events[type] = eventsByType;
		
		Events.listByElement[element.signature] = events;
		
		element.htmlElement.addEventListener(type, callback);
	},
	
	fire: function(element, type) {
		var events = element.events(type);
		events.forEach(function(callback) {
			callback.apply(element.htmlElement);
		});
	}
};

/**
 * Création d'élément HTML
 */
class ElementHTML {
	
	// tagName : null, 		// li, a, img, p
	// attributes : null, 	// tableau des attributs
	// htmlElement : null, 	// Objet HTMLElement natif
	// signature : null
	
	constructor(tagName, attributes, htmlElement) {
		
		this.htmlElement = htmlElement || null;
		
		if(this.htmlElement === null) {
			this.tagName = tagName;
			if(this.tagName === 'text') {
				this.attributes = (typeof attributes === 'string') ? attributes : '';
			} else {
				this.attributes = (typeof attributes === 'object') ? attributes : {};
			}
			this.initHtmlElement();
		} else { // Si on instancie depuis un objet htmlElement
			this.tagName = (htmlElement.tagName) ? htmlElement.tagName.toLowerCase() : 'text';
			this.attributes = {};
			if(htmlElement.attributes) {
				for(var indexAttribute = 0 ; indexAttribute < htmlElement.attributes.length ; indexAttribute++) {
					var attribute = htmlElement.attributes[indexAttribute];
					this.attributes[attribute.name] = attribute.value;
				}
			}
		}
		
		if(! this.htmlElement.rootJSSignature) {
			this.htmlElement.rootJSSignature = Str.uniqueId();
		}
		
		this.signature = this.htmlElement.rootJSSignature;
	};
	
	/**
	 * Création de l'élément
	 * @return void
	 */
	initHtmlElement() {
		
		if(this.htmlElement !== null) {
			return;
		}
		
		// Création de l'élément
		var element = document.createElement(this.tagName);
		// Affectation des propriétés HTML
		for(var attributeName in this.attributes) {
			if(attributeName === 'html') {
				element.innerHTML = this.attributes[attributeName];
			} else if(attributeName === 'text') {
				element.appendChild(document.createTextNode(this.attributes[attributeName]));
			} else {
				element.setAttribute(attributeName, this.attributes[attributeName]);
			}
		}
		
		
		this.htmlElement = element;
		
	};
	
	/**
	 * Retourne un élément à partir d'un sélecteur CSS
	 * @param string selector
	 * @return self
	 */
	static searchOne(selector, root) {
		
		root = root || document;
		
		var htmlElement = root.querySelector(selector);
		if(htmlElement === null) {
			return null;
		}
		return ElementHTML.retrieve(htmlElement);
	};
	
	/**
	 * Retourne une liste d'éléments à partir d'un sélecteur CSS
	 * @param string selector
	 * @return array
	 */
	static searchList(selector, root) {
		root = root || document;
		var elements = [],
			nodeList = root.querySelectorAll(selector)
		;
		
		nodeList.forEach(function(element) {
			elements.push(ElementHTML.retrieve(element));
		});

		return elements;
	};
	
	/**
	 * Retourne une instance à partir d'un objet natif HTMLElement
	 * @return self
	 */
	static retrieve(element) {
		return new ElementHTML(null, null, element);
	};
	
	/**
	 * Retourne l'élément précédent correspondant au sélecteur en paramètre
	 * @param string selector
	 * @return self
	 */
	getPrevious(selector) {
		this._htmlElementRequired();
		
		var next = null,
			element = this.htmlElement.previousElementSibling
		;
	
		while(element) {
			if(element.matches(selector)) {
				return ElementHTML.retrieve(element);
			}
			element = element.previousElementSibling;
		}
		
		return null;
	};
	
	/**
	 * Retourne l'élément suivant correspondant au sélecteur en paramètre
	 * @param string selector
	 * @return self
	 */
	getNext(selector) {
		this._htmlElementRequired();
		
		var next = null,
			element = this.htmlElement.nextElementSibling
		;

		while(element) {
			if(element.matches(selector)) {
				return ElementHTML.retrieve(element);
			}
			element = element.nextElementSibling;
		}
		
		return null;
	};
	
	/**
	 * Recherche un élément parent
	 * @param string selector Sélecteur du parent à retourner
	 * @return self
	 */
	getParent(selector) {
		this._htmlElementRequired();
		
		var parent = this.htmlElement;
		
		try {
			do {
	            if (parent.matches(selector)) {
	            	return ElementHTML.retrieve(parent);
	            }
	            parent = parent.parentElement || parent.parentNode;
	        } while (parent !== null); 
		} catch(e) {
			
		}
        
        return null;
	};
	
	/**
	 * Recherche un élément parmi les enfants de l'élément courant
	 * @param string selector
	 * @return ElementHTML
	 */
	getChild(selector) {
		this._htmlElementRequired();
		return ElementHTML.searchOne(selector, this.htmlElement);
	};
	
	/**
	 * Recherche les enfants de l'élément courant
	 * @param string selector
	 * @return ElementHTML
	 */
	getChildren(selector) {
		this._htmlElementRequired();
		return ElementHTML.searchList(selector, this.htmlElement); 
	};
	
	/**
	 * Recherche le dernier élément du sélecteur
	 * @param string selector
	 * @return ElementHTML
	 */
	getLast(selector) {
		this._htmlElementRequired();
		
		var elements = this.getChildren(selector);
		
		if(elements.length > 0) {
			return elements[elements.length - 1];
		}
		
		return null;
	};
	
	/**
	 * Ajoute un élément à l'élément courant
	 * @param ElementHTML elementHTML
	 * @param string position Position où ajouter l'élément
	 * 						  before, after, top ; si non renseigné, on ajoute l'élément à la fin du contenu
	 * @return self
	 */
	addElement(elementHTML, position = 'bottom') {
		
		var allowedPositions = [ 'bottom', 'before', 'after', 'top', ];
		
		this._htmlElementRequired();
		
		if(allowedPositions.indexOf(position) === -1) {
			throw 'Position de l\'élément incorrect.';
		}
		
		switch(position) {
			case 'bottom':
				this.htmlElement.appendChild(elementHTML.htmlElement);
				break;
			case 'after':
				this.htmlElement.parentNode.insertBefore(elementHTML.htmlElement, this.htmlElement.nextSibling);
				break;
			case 'before':
				this.htmlElement.parentNode.insertBefore(elementHTML.htmlElement, this.htmlElement);
				break;
			case 'top':
				if(this.htmlElement.hasChildNodes()) {
					this.htmlElement.firstChild.parentNode.insertBefore(elementHTML.htmlElement, this.htmlElement.firstChild);
				} else {
					this.htmlElement.appendChild(elementHTML.htmlElement);
				}
		}
		
		
		return this;
		
	};
	
	/**
	 * Modifit la valeur d'une propriété de l'élément courant
	 * @param string property
	 * @param mixed value
	 * @return self
	 */
	setProperty(property, value) {
		
		this._htmlElementRequired();
		
		if(property == 'value' && this.tagName == 'input') {
			this.htmlElement.value = value;
		}
		
		if(property === 'text') {
			this.htmlElement.innerText = value;
		} else {
			this.htmlElement.setAttribute(property, value);
		}
		
		this.attributes[property] = value;
		
		return this;
	};
	
	/**
	 * Modification de plusieurs propriété de l'élément courant
	 * @param object properties
	 * @return self
	 */
	setProperties(properties) {
		this._htmlElementRequired();
		
		var self = this;
		
		Object.keys(properties).forEach(function(key) {
			self.setProperty(key, properties[key]);
		});
		
		return this;
	};

	
	/**
	 * Retourne la valeur d'une propriété de l'élément courant
	 * @param string property
	 * @return mixed
	 */
	getProperty(property) {
		this._htmlElementRequired();
		
		var value = null;

		if(property === 'text') {
			value = this.htmlElement.innerText;
		} else if(property in this.htmlElement) {
			value = this.htmlElement.value;
		} else if(this.attributes.hasOwnProperty(property)) {
			value = this.attributes[property];
		}
		
		return value;
	};
	
	/**
	 * Retourne l'élément sélectionné d'un select
	 * @return self
	 */
	getSelectedElement() {
		
		this._htmlElementRequired();
		
		return this.getChild(':checked');
	};
	
	/**
	 * Ajoute une ou plusieurs classes CSS à l'élément courant
	 * @param string classes La ou les classes à ajouter
	 * @return self
	 */
	addClass(classes) {
		this._htmlElementRequired();
		
		if(this.hasClass(classes)) {
			return this;
		}
		
		this.htmlElement.classList.add(classes);

		return this;
	};
	
	/**
	 * Supprime la classe CSS de l'élément courant
	 * @patam string classToDelete
	 * @return self
	 */
	removeClass(classToDelete) {
		this.htmlElement.classList.remove(classToDelete);
		this.setProperty('class', this.htmlElement.className);
		return this;
	};
	
	/**
	 * Retourne si la classe en paramètre existe pour l'élément
	 * @param string classToCheck
	 * @return bool
	 */
	hasClass(classToCheck) {
		return this.htmlElement.classList.contains(classToCheck);
	};

	
	/**
	 * Retourne les événements de l'objet du type d'évvénement en paramètre
	 * @param string event
	 * @return Object
	 */
	events(event) {
		var events = Events.listByElement[this.signature] || {};
		if(event) {
			events = (events[event] || []);
		}
		return events;
	};
	
	/**
	 * Ajoute un événement sur l'élèment courant
	 * @param string event Nom de l'événement à éxécuter
	 * @param function callback La méthode éxécutant l'événement à éxécuter
	 * @return self
	 */
	addEvent(event, callback) {
		
		this._htmlElementRequired();
		
		if(typeof callback !== 'function') {
			throw 'La méthode de l\'évenement est incorrecte.';
		}
		
		Events.add(this, event, callback);
		
		return this;
	};
	
	/**
	 * Ajoute plusieurs événements
	 * @param object $events
	 * @return self
	 */
	addEvents(events) {
		
		this._htmlElementRequired();
		
		var self = this;
		
		Object.entries(events).forEach(function([event, callback]) {
			self.addEvent(event, callback);
		});
		
	};
	
	/**
	 * Déclenche un événement sur l'élément courant
	 * @param string Event event
	 * @return self
	 */
	fireEvent(event) {
		this._htmlElementRequired();
		
		Events.fire(this, event);
	};
	
	/**
	 * Vide le contnu
	 * @return self
	 */
	empty() {
		this._htmlElementRequired();
		this.htmlElement.innerHTML = '';
	};
	
	/**
	 * Suppression de l'élément courant 
	 * @return void
	 */
	remove() {
		this._htmlElementRequired();
		this.htmlElement.parentNode.removeChild(this.htmlElement);
	};
	
	/**
	 * Supprime un élément enfant de l'élément courant
	 * @param ElementHTML element l'élément à supprimer de l'élément courant
	 * @return self
	 */
	removeChild(element) {
		this._htmlElementRequired();
		element._htmlElementRequired();
		this.htmlElement.removeChild(element.htmlElement);
	};
	
	/**
	 * Supprime tous les enfants de l'élément courant
	 * @return self
	 */
	removeChildren() {
		this._htmlElementRequired();
		while(this.htmlElement.firstChild) {
			this.removeChild(ElementHTML.retrieve(this.htmlElement.firstChild));
		}
	};
	
	/**
	 * Méthode appelée lorsque l'élément HTML n'a pas été appelé et qu'il est nécessaire
	 * @return void 
	 */
	_htmlElementRequired() {
		if(this.htmlElement === null) {
			throw 'L\'élément n\'a pas été initialisé.';
		}
	};
	
}