
/**
 * Root JS
 */

class RootJS {
	
	// settings: null
	
	constructor(/*settings*/) {
	    // this.settings = settings;
	};
	
	execute() {
		
		/*var controllerClass = this.settings.controller.className,
			controller = null;*/
		
		try {
			if(window.initRootJS && typeof window.initRootJS == 'function') {
				window.initRootJS();
			}

		} catch(e) {
			console.log(e);
		}
		
		/*if(controller !== null) {
			controller.execute();
		}*/
		
	};
	
};

