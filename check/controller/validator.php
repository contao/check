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
 * Validate and existing Contao installation
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
 */
class Validator
{

	/**
	 * Valid installation
	 * @var boolean
	 */
	protected $valid = true;

	/**
	 * Constants found
	 * @var boolean
	 */
	protected $constants = true;

	/**
	 * Version found
	 * @var boolean
	 */
	protected $version = true;

	/**
	 * Errors
	 * @var array
	 */
	protected $errors = array();


	/**
	 * Check whether there is a Contao installation
	 */
	public function run()
	{
		if (!$this->findConstants() || !$this->checkVersion()) {
			$this->valid = false;
		} else {
			$this->validate();
		}

		include 'views/validator.phtml';
	}


	/**
	 * Check whether the constants.php file has been found
	 *
	 * @return boolean True if the constants.php file has been found
	 */
	public function hasConstants()
	{
		return $this->constants;
	}


	/**
	 * Check whether the Contao version is supported
	 *
	 * @return boolean True if the Contao version is supported
	 */
	public function isSupportedVersion()
	{
		return $this->version;
	}


	/**
	 * Check whether there are missing files
	 *
	 * @return boolean True if there are missing files
	 */
	public function hasMissing()
	{
		return !empty($this->errors['missing']);
	}


	/**
	 * Return the missing files as array
	 *
	 * @return array The missing files array
	 */
	public function getMissing()
	{
		return $this->errors['missing'];
	}


	/**
	 * Check whether there are corrupt files
	 *
	 * @return boolean True if there are corrupt files
	 */
	public function hasCorrupt()
	{
		return !empty($this->errors['corrupt']);
	}


	/**
	 * Return the corrupt files as array
	 *
	 * @return array The corrupt files array
	 */
	public function getCorrupt()
	{
		return $this->errors['corrupt'];
	}


	/**
	 * Check whether the installation is vaild
	 *
	 * @return boolean True if the installation is valid
	 */
	public function isValid()
	{
		return $this->valid;
	}


	/**
	 * Find the constants.php file
	 *
	 * @return boolean True if the constants.php file was found
	 */
	protected function findConstants()
	{
		if (file_exists(TL_ROOT . '/system/constants.php')) {
			include TL_ROOT . '/system/constants.php';
		} elseif (file_exists(TL_ROOT . '/system/config/constants.php')) {
			include TL_ROOT . '/system/config/constants.php';
		} else {
			$this->constants = false;
			return false;
		}

		return true;
	}


	/**
	 * Check whether the Contao version is supported
	 *
	 * @return boolean True if the Contao version is supported
	 */
	protected function checkVersion()
	{
		$file = 'versions/' . VERSION . '.' . BUILD . '.json';

		if (!file_exists($file)) {
			$this->version = false;
			return false;
		}

		return true;
	}


	/**
	 * Validate the installation
	 */
	protected function validate()
	{
		$this->errors = array('missing'=>array(), 'corrupt'=>array());

		// Load the file hashes
		$file = 'versions/' . VERSION . '.' . BUILD . '.json';
		$hashes = json_decode(file_get_contents($file));

		foreach ($hashes as $info) {
			list($path, $md5_file) = $info;

			if (!file_exists(TL_ROOT . '/' . $path)) {
				$this->valid = false;
				$this->errors['missing'][] = $path;
			} else {
				$buffer = str_replace("\r", '', file_get_contents(TL_ROOT . '/' . $path));

				// Check the MD5 hash
				if (strncmp(md5($buffer), $md5_file, 10) !== 0) {
					$this->valid = false;
					$this->errors['corrupt'][] = $path;
				}

				$buffer = null;
			}
		}
	}
}
