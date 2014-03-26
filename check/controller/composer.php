<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package Check
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Check the Composer package manager requirements
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2014
 */
class Composer
{

	/**
	 * Execute the command
	 */
	public function run()
	{
		include 'views/composer.phtml';
	}


	/**
	 * Availability
	 * @var boolean
	 */
	protected $available = true;


	/**
	 * Return the availability of the Composer package manager
	 *
	 * @return boolean True if the Composer package manager can be used
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
	 * Check whether the PHP cURL extension is available
	 *
	 * @return boolean True if the PHP cURL extension is available
	 */
	public function hasCurl()
	{
		if (function_exists('curl_init')) {
			return true;
		}

		$this->available = false;
		return false;
	}


	/**
	 * Check whether the PHP APC extension is installed
	 *
	 * @return boolean True if the PHP APC extension is installed
	 */
	public function hasApc()
	{
		if (!extension_loaded('apcu')) {
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

		if ($suhosin === false) {
			return false;
		}

		$allowed = array_map('trim', explode(',', $suhosin));

		// The previous check returned false positives for e.g. "phar."
		if (in_array('phar', $allowed) || in_array('phar://', $allowed)) {
			return false;
		}

		$this->available = false;
		return true;
	}


	/**
	 * Check whether "allow_url_fopen" is enabled
	 *
	 * @return boolean True if "allow_url_fopen" is enabled
	 */
	public function hasAllowUrlFopen()
	{
		if (ini_get('allow_url_fopen')) {
			return true;
		}

		$this->available = false;
		return false;
	}


	/**
	 * Return true if the Safe Mode Hack is required
	 *
	 * @return boolean True if the Safe Mode Hack is required
	 */
	public function requiresSafeModeHack()
	{
		include 'safe-mode-hack.php';
		$smh = new SafeModeHack;

		if ($smh->isEnabled()) {
			return true;
		}

		if (!$smh->canCreateFolder()) {
			return true;
		}

		if (!$smh->canCreateFile()) {
			return true;
		}

		return false;
	}


	/**
	 * Check whether the PHP shell_exec function is available
	 *
	 * @return boolean True if the PHP shell_exec function is available
	 */
	public function hasShellExec()
	{
		if (function_exists('shell_exec')) {
			return true;
		}

		return false;
	}


	/**
	 * Check whether the PHP proc_open function is available
	 *
	 * @return boolean True if the PHP proc_open function is available
	 */
	public function hasProcOpen()
	{
		if (function_exists('proc_open')) {
			return true;
		}

		return false;
	}
}
