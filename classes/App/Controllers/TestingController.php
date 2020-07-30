<?php

/**
 * Page de test
 */

namespace App\Controllers;

use Root\{ Controller };

class TestingController extends Controller {
	
	public function index() : void
	{
		$pattern = '/[^(.gitignore)]/';
		$file = '.gitignore';
		$response = preg_match($pattern, $file);
		var_dump($response);
		
		exit;
	}
	
}