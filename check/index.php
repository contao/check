<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package Check
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

require 'controller/bootstrap.php';


/**
 * Route the request to a controller
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
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
