/**
 * Gestion d'un bouton permettant d'ajouter ou retirer un livre d'une liste de lecture
 */
class ManageListButton extends Component {
	
	// component: null,
	
	initEvents() {
		var self = this; 
		this.component.addEvent('click', function(event) {
			event.preventDefault();
			if(! self._loading) {
				self.send();
			}
		});
		
	};
	
	/**
	 * Envoi de la requête en Ajax
	 * @return void
	 */
	send() {
		
		var self = this,
			type = this.component.getProperty('data-type'),
			newType = (type == 'add') ? 'delete' : 'add',
			newDescription = this.component.getProperty('data-description-' + newType),
			urlKey = 'data-url-' + type
		;
		
		this.loading(true);
		
		new JSONAjaxRequest({
			"url": this.component.getProperty(urlKey),
			"method": "post",
			"onComplete": function() {
				self.loading(false);
			},
			"onSuccess": function(response) {
				if(newType == 'add') {
					self.component.removeClass('selected');
				} else {
					self.component.addClass('selected');
				}
				
				self.component.setProperties({
					'data-type': newType,
					'title': newDescription
				});
			}
		});
	};
	
	showLoading() {
		this.component.addClass('loading');
	};
	
	hideLoading() {
		this.component.removeClass('loading');
	};
	
};