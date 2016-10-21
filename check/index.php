<?php

/*
 * Contao check
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

require __DIR__ . '/controller/bootstrap.php';

/**
 * Route the request to a controller
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Router
{
	/**
	 * Dispatch a request and send the response
	 *
	 * @throws RuntimeException If the command name is invalid
	 */
	public static function dispatch()
	{
		// Default command
		$file = 'controller/index.php';
		$class = 'Index';

		$command = filter_var($_GET['c'], FILTER_SANITIZE_STRING);

		// Check the command (thanks to Arnaud Buchoux)
		if (static::isInsecurePath($command)) {
			throw new RuntimeException("Invalid command $command");
		}

		// Custom command
		if ($command != '' && file_exists("controller/$command.php")) {
			$file = "controller/$command.php";
			$class = str_replace(' ', '', ucwords(str_replace('-', ' ', $command)));
		}

		include $file;

		header('Content-Type: text/html; charset=utf-8');
		$controller = new $class();
		$controller->run();
	}

	/**
	 * Insecure path potentially containing directory traversal
	 *
	 * @param string $path The file path
	 *
	 * @return boolean True if the file path is insecure
	 */
	public static function isInsecurePath($path)
	{
		// Normalize backslashes
		$path = str_replace('\\', '/', $path);
		$path = preg_replace('#//+#', '/', $path);

		// Equals ..
		if ($path == '..')
		{
			return true;
		}

		// Begins with ./
		if (substr($path, 0, 2) == './')
		{
			return true;
		}

		// Begins with ../
		if (substr($path, 0, 3) == '../')
		{
			return true;
		}

		// Ends with /.
		if (substr($path, -2) == '/.')
		{
			return true;
		}

		// Ends with /..
		if (substr($path, -3) == '/..')
		{
			return true;
		}

		// Contains /../
		if (strpos($path, '/../') !== false)
		{
			return true;
		}

		return false;
	}
}

Router::dispatch();
