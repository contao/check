<?php

/*
 * This file is part of the Contao Check.
 *
 * (c) Fritz Michael Gschwantner
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

/**
 * Check the Extension Repository requirements
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Repository
{
	/**
	 * @var boolean
	 */
	protected $available = true;

	/**
	 * Execute the command
	 */
	public function run()
	{
		include __DIR__ . '/../views/repository.phtml';
	}

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
	 * Check whether the PHP OpenSSL extension is available
	 *
	 * @return boolean True if the PHP OpenSSL extension is available
	 */
	public function hasSsl()
	{
		if (extension_loaded('openssl')) {
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
		$connection = @fsockopen('ssl://contao.org', 443, $errno, $errstr, 10);

		if ($connection !== false) {
			fclose($connection);
			return true;
		}

		$this->available = false;

		return false;
	}
}
