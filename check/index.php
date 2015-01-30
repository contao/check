<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

require 'controller/bootstrap.php';


/**
 * Route the request to a controller
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Router
{

	/**
	 * Dispatch a request and send the response
	 */
	public static function dispatch()
	{
		// Default command
		$file  = 'controller/index.php';
		$class = 'Index';

		$command = filter_var($_GET['c'], FILTER_SANITIZE_STRING);

		// Custom command
		if ($command != '' && file_exists("controller/$command.php")) {
			$file = "controller/$command.php";
			$class = str_replace(' ', '', ucwords(str_replace('-', ' ', $command)));
		}

		include $file;
		$controller = new $class;
		$controller->run();
	}
}

Router::dispatch();
