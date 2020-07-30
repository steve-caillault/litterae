/**
 * Gestion d'un diaporama
 */

class Slideshow extends Component {
	
	// component: null,
	// currentDoubleClick: false,
	// imageSelector: 'div.item',
	
	constructor(component, ready) {
		super(component, ready);
		this.currentDoubleClick = false;
		this.imageSelector = 'div.item';
	};
	
	initEvents() {
		var self = this;
		
		// Lors d'un clic, affichage de la prochaine image
		this.component.addEvent('click', function(event) {
			event.preventDefault();
			setTimeout(function() {
				if(! self.currentDoubleClick) {
					self.showNextImage();
				}
			}, 200);
			
		});
		
		// Lors d'un double clic, affichage de l'image dans un onglet
		// @todo Faire la gestion des boîtes de dialogue
		this.component.addEvent('dblclick', function(event) {
			self.currentDoubleClick = true;
			self.showZoomImage();
			setTimeout(function() {
				self.currentDoubleClick = false;
			}, 500);
		});
	};
	
	/**
	 * Affichage de l'image en plus grand
	 */
	showZoomImage() {
		var image = this.component.getChild(this.imageSelector + ':not(.hidden)'),
			url = (image == null) ? null : image.getProperty('data-url')
		;
		if(image == null) {
			return;
		}
		
		window.open(url);
	};
	
	/**
	 * Affichage de la prochaine image
	 * @return void
	 */
	showNextImage() {
		
		var currentImage = this.component.getChild(this.imageSelector + ':not(.hidden)'),
			nextImage = currentImage.getNext(this.imageSelector + '.hidden')
		;
	
		// Si la prochaine image n'a pas été trouvé, on prend la première image de la liste
		if(nextImage == null) {
			nextImage = this.component.getChild(this.imageSelector + '.hidden');
		}
		
		if(nextImage != null) {
			currentImage.addClass('hidden');
			nextImage.removeClass('hidden');
		}
	};
	
};