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


/**
 * Check the Live Update requirements
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
 */
class LiveUpdate
{

	/**
	 * Availability
	 * @var boolean
	 */
	protected $available = true;


	/**
	 * Execute the command
	 */
	public function run()
	{
		include 'views/live-update.phtml';
	}


	/**
	 * Return the availability of the Live Update
	 *
	 * @return boolean True if the Live Update can be used
	 */
	public function isAvailable()
	{
		return $this->available;
	}


	/**
	 * Check whether the PHP version is at least 5.3.2
	 *
	 * @return boolean True if the PHP version is at least 5.3.2
	 */
	public function hasPhp532()
	{
		if (version_compare(phpversion(), '5.3.2', '>=')) {
			return true;
		}

		$this->available = false;
		return false;
	}


	/**
	 * Check whether the PHP Phar extension is available
	 *
	 * @return boolean True if the PHP Phar extension is available
	 */
	public function hasPhar()
	{
		if (extension_loaded('Phar')) {
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
	 * Check whether the ionCube Loader is enabled
	 *
	 * @return boolean True if the ionCube Loader is enabled
	 */
	public function hasIonCube()
	{
		if (!extension_loaded('ionCube Loader')) {
			return false;
		}

		// The issues have been fixed in version 4.0.9
		if (ioncube_loader_iversion() >= 40009) {
			return false;
		}

		$this->available = false;
		return true;
	}


	/**
	 * Check whether the PHP Suhosin extension is enabled
	 *
	 * @return boolean True if the PHP Suhosin extension is enabled
	 */
	public function hasSuhosin()
	{
		$suhosin = ini_get('suhosin.executor.include.whitelist');

		if ($suhosin === false || stripos($suhosin, 'phar') !== false) {
			return false;
		}

		$this->available = false;
		return true;
	}


	/**
	 * Check whether detect_unicode is enabled
	 *
	 * @return boolean True if detect_unicode is enabled
	 */
	public function hasDetectUnicode()
	{
		$multibyte = ini_get('zend.multibyte');

		// Zend multibyte has been disabled (see #28)
		if ($multibyte !== false) {
			if ($multibyte == '' || $multibyte == 0 || $multibyte == 'Off') {
				return false;
			}
		}

		// Determine the correct parameter name (see #28)
		if (version_compare(phpversion(), '5.4', '<')) {
			$name = 'detect_unicode';
		} else {
			$name ='zend.detect_unicode';
		}

		$unicode = ini_get($name);

		// Detect_unicode has been disabled
		if ($unicode == '' || $unicode == 0 || $unicode == 'Off') {
			return false;
		}

		$this->available = false;
		return true;
	}


	/**
	 * Check whether PHP is run as FastCGI with the eAccelerator
	 *
	 * @return boolean True if PHP is run as FastCGI with the eAccelerator
	 */
	public function isFastCgiEaccelerator()
	{
		$fast_cgi = (php_sapi_name() == 'cgi-fcgi');
		$eaccelerator = extension_loaded('eaccelerator') && ini_get('eaccelerator.enable');

		if (!$fast_cgi || !$eaccelerator) {
			return false;
		}

		$this->available = false;
		return true;
	}


	/**
	 * Check whether a connection can be established
	 */
	public function canConnect()
	{
		$connection = fsockopen('ssl://www.inetrobots.com', 443, $errno, $errstr, 10);
		$connected = ($connection !== false);
		fclose($connection);

		if ($connected) {
			return true;
		}

		$this->available = false;
		return false;
	}
}
