/**
 * Gestion des contributeurs
 */

/**
 * Gestion de la suppression du contributeur d'un livre
 */
class DeleteContributor extends Component {
	
	// component: null,
	
	/**
	 * Gestion des événements
	 * @return void
	 */
	initEvents() {
		
		var self = this;
		
		this.component.addEvent('click', function(event) {
			event.preventDefault();
			
			if(! self._loading) {
				self.send();
			}
		});
		
		this.component.addEvent('delete', function() {
			self.onDelete();
		})
		
	};
	
	/**
	 * Appel Ajax pour la suppression
	 * @return void
	 */
	send() {
		
		var self = this;
		
		this.loading(true);
		
		new JSONAjaxRequest({
			"url": this.component.getProperty('data-url'),
			"method": "post",
			"onComplete": function() {
				self.loading(false);
			},
			"onSuccess": function(response) {
				if(response) {
					self.component.fireEvent('delete');
				}
			}
		});
		
	};
	
	/**
	 * Méthode a exécuter lors de la suppression
	 * @return void
	 */
	onDelete() {
		var table = this.component.getParent('table');
		
		this.component.getParent('tr.line').remove();
		
		// Suppression du tableau s'il n'y a plus d'élément
		if(table.getChildren('tr.line').length == 0) {
			table.remove();
		}
		
	};
	
	showLoading() {
		this.component.getParent('tr.line').addClass('loading');
	};
	
	hideLoading() {
		this.component.getParent('tr.line').removeClass('loading');
	};
	
};

/**
 * Gestion du formulaire d'ajout de contributeur d'un livre
 */
class AddContributorForm extends Component {
	
	// component: null,
	
	/**
	 * Gestion des événements
	 * @return void
	 */
	initEvents() {
		
		var self = this;
		
		this.component.addEvent('submit', function(event) {
			event.preventDefault();
			
			if(! self._loading) {
				var value = ElementHTML.retrieve(this).getChild('input.autocomplete-value').getProperty('value');
				if(value) {
					self.send(value);
				}
			}
		});
	};
	
	/**
	 * Envoi de la requête en Ajax
	 * @param string value L'identifiant du contributor
	 * @return void
	 */
	send(value) {
		
		var self = this,
			paramKey = this.component.getProperty('data-input-name'),
			params = {}
		;
		
		params[paramKey] = value;
		params['form_name'] = this.component.getChild('input[name=form_name]').getProperty('value');
		
		this.loading(true);
		
		new JSONAjaxRequest({
			"url": this.component.getProperty('data-add-contributor-url'),
			"method": "post",
			"params": params,
			"onComplete": function() {
				self.loading(false);
			},
			"onSuccess": function(response) {
				var contributor = response.contributor;
				if(contributor) {
					self.addContributor(contributor);
				}
			}
		});
		
	};
	
	/**
	 * Ajout du contributeur en paramètre au tableu de la liste
	 * @param object contributor
	 * @return void
	 */
	addContributor(contributor) {
		var contributorType = this.component.getProperty('data-contributor-type'),
			collection = ElementHTML.searchOne('div.contributor-' + contributorType + '-collection'),
			table = collection.getChild('table'),
			/***/
			editAnchor = new ElementHTML('a', {
				'href': contributor.editURL,
				'title': 'Modifier ' + contributor.name + '.',
				'text': contributor.name
			}),
			deleteAnchor = new ElementHTML('a', {
				'href': contributor.deleteURL,
				'title': 'Supprimer ' + contributor.name + '.',
				'html': '&times;',
				'data-url': contributor.ajaxDeleteURL
			}),
			/***/
			tdEditAnchor = new ElementHTML('td').addElement(editAnchor),
			tdDeleteAnchor = new ElementHTML('td', {
				'class': 'with-delete'
			}).addElement(deleteAnchor),
			/***/
			tr = new ElementHTML('tr', {
				'class': 'line'
			}).addElement(tdEditAnchor).addElement(tdDeleteAnchor)
		;
		
		// Gestion de l'ancre de suppression du contributeur
		new DeleteContributor(deleteAnchor);
		
		// Cré le tableau s'il n'existe pas
		if(table == null) {	
			table = new ElementHTML('table', {
				'class': 'without-header'
			}); 
			collection.addElement(table);
		}
		
		// Ajout de la ligne
		table.addElement(tr);
	};
	
	showLoading() {
		this.component.addClass('loading');
	};
	
	hideLoading() {
		this.component.removeClass('loading');
	};
	
};