/**
 * Fonction d'initialisation appelée dans la classe RootJS
 */

var initRootJS = function() {

	// Gestion des champs d'autocomplètion (commun)
	ElementHTML.searchList('div.autocomplete-search:not(.init)').forEach(function(element) {
		new FormAutocomplete(element);
	});
	
	// Gestion des diaporamas (site)
	ElementHTML.searchList('div.slideshow:not(.init)').forEach(function(element) {
		new Slideshow(element);
	});
	
	// Gestion des boutons d'ajout à des listes (site)
	ElementHTML.searchList('button.manage-list').forEach(function(element) {
		new ManageListButton(element);
	});
	
	// Gestion de l'ajout de contributeur (admin)
	ElementHTML.searchList('form.add-contributor-form').forEach(function(element) {
		new AddContributorForm(element);
	});
	
	// Gestion de la suppression des contributeurs (admin)
	ElementHTML.searchList('div.contributor-collection td.with-delete a').forEach(function(element) {
		new DeleteContributor(element);
	});
};

// Initialisation du site
document.addEventListener('DOMContentLoaded', function() { 
	(new RootJS()).execute();
}, false);