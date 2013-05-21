<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package Check
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Check the Extension Repository requirements
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2013
 */
class Repository
{

	/**
	 * Execute the command
	 */
	public function run()
	{
		include 'views/repository.phtml';
	}


	/**
	 * Availability
	 * @var boolean
	 */
	protected $available = true;


	/**
	 * Return the availability of the Extension Repository
	 *
	 * @return boolean True if the Extension Repository can be used
	 */
	public function isAvailable()
	{
		return $this->available;
	}


	/**
	 * Check whether the PHP SOAP extension is available
	 *
	 * @return boolean True if the PHP SOAP extension is available
	 */
	public function hasSoap()
	{
		if (extension_loaded('soap')) {
			return true;
		}

		$this->available = false;
		return false;
	}


	/**
	 * Check whether a connection can be established
	 */
	public function canConnect()
	{
		$connection = fsockopen('contao.org', 80, $errno, $errstr, 10);
		$connected = ($connection !== false);
		fclose($connection);

		if ($connected) {
			return true;
		}

		$this->available = false;
		return false;
	}
}
