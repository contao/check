<?php

/*
 * Contao check
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

require_once __DIR__ . '/controller/contao2.php';
require_once __DIR__ . '/controller/contao3.php';
require_once __DIR__ . '/controller/contao4.php';
require_once __DIR__ . '/controller/contao45.php';
require_once __DIR__ . '/controller/composer.php';
require_once __DIR__ . '/controller/file-permissions.php';
require_once __DIR__ . '/controller/live-update.php';
require_once __DIR__ . '/controller/repository.php';

/**
 * CLI interface
 *
 * @author Fritz Michael Gschwantner <https://github.com/fritzmg>
 */
class Cli
{
	protected static $arrControllers = [
		'Contao2',
		'Contao3',
		'Contao4',
		'Contao45',
		'Composer',
		'FilePermissions',
		'LiveUpdate',
		'Repository'
	];

	public function run($argv)
	{
		echo "\n";

		if (isset($argv[1]) && in_array($argv[1], self::$arrControllers))
		{
			$controller = new $argv[1];
			$methods = get_class_methods($controller);

			echo "{$argv[1]}:\n";

			foreach ($methods as $method)
			{
				if (strpos($method, 'can') === 0 || strpos($method, 'has') === 0)
				{
					echo "  $method: " . ($controller->{$method}() ? 'true' : 'false') . "\n";
				}
			}
		}
		else
		{
			foreach (self::$arrControllers as $strController)
			{
				$controller = new $strController;
				$methods = get_class_methods($controller);

				foreach ($methods as $method)
				{
					if ($method == 'checkCompatibility')
					{
						echo "$strController: " . ($controller->{$method}() ? 'compatible' : 'not compatible') . "\n";
					}
				}
			}

			echo "\nRun the command again with one of these as an argument, to show more information:\n" . implode(', ', self::$arrControllers) . "\n";
			echo "\nExample:\n$ php cli.php Contao45\n";
		}
	}
}

$cli = new Cli();
$cli->run($argv);
