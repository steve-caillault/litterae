<?php

/**
 * Gestion des exceptions
 */

namespace App\Exceptions;

use Root\{ Config, Route, Request, Debug };
use Root\Response;

class HttpException {
	
	/**
	 * Gestionnaire d'exception
	 * @param \Throwable $exception
	 * @return void
	 */
	public static function handler(\Throwable $exception) : void
	{
		$code = $exception->getCode();
		$message = $exception->getMessage();
		
		$modeDebug = Config::load('beyond.debug');
		if($modeDebug)
		{
			exit(Debug::show($exception));
		}
		$allowedCodes = [ 401, 403, 404, 500, ];
		if(! in_array($code, $allowedCodes))
		{
			$code = 500;
			$message = '';
		}
		
		if(! $message)
		{
			switch($code)
			{
				case 401:
					$message = 'Vous devez être identifié pour accéder à cette page.';
					break;
				case 403:
					$message = 'Vous n\'êtes pas autorisé à accéder à cette page.';
					break;
				case 404:
					$message = 'Cette page n\'existe pas ou a été déplacé.';
					break;
				case 500:
					$message = 'Une erreur s\'est produite.';
					break;
			}
		}
		
		if($code == 500)
		{
			$message = 'Une erreur s\'est produite.';
		}
		
		$response = NULL;
		
		try {
			$currentRequest = Request::current(); 
			$isAjax = $currentRequest->isAjax();
		} catch(\Throwable $exceptionRequest) {
			$isAjax = FALSE;
		}

		if($isAjax)
		{
			$json = json_encode([
				'error' => [
					'code' => $code,
					'message' => $message,
				],
			]);
			$response = new Response($json);
			$response->addHeader('Content-Type', 'application/json');
		}
		else
		{
			try {
				$request = Request::factory([
					'route' => Route::retrieve('error'),
				]);
				
				$request->post([
					'code' => $code,
					'message' => $message,
				]);
				
				$response = Request::current($request)->response();

			} catch(\Throwable $exceptionCritic) {
				// debug($exceptionCritic, TRUE);
				$response = 'Une erreur s\'est produite.';
			} 
		}
		
		http_response_code($code);
		
		exit($response);
	}
	
}