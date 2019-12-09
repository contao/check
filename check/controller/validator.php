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
 * Validate and existing Contao installation
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Validator
{
	/**
	 * @var boolean
	 */
	protected $valid = true;

	/**
	 * @var boolean
	 */
	protected $constants = true;

	/**
	 * @var boolean
	 */
	protected $version = true;

	/**
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Check whether there is a Contao installation
	 */
	public function run()
	{
		if (!$this->findConstants()) {
			$this->valid = false;
		} else {
			if (version_compare(VERSION, '4', '>=')) {
				$this->errors['version'] = sprintf(__('Contao %s cannot be validated with the Contao Check.'), explode('.', VERSION)[0]);
				$this->valid = false;
			} elseif (!$this->getVersionFile()) {
				$this->valid = false;
			} else {
				$this->validate();
			}
		}

		include __DIR__ . '/../views/validator.phtml';
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
	 * Check whether there are optional files
	 *
	 * @return boolean True if there are optional files
	 */
	public function hasOptional()
	{
		return !empty($this->errors['optional']);
	}

	/**
	 * Return the optional files as array
	 *
	 * @return array The optional files array
	 */
	public function getOptional()
	{
		return $this->errors['optional'];
	}

	/**
	 * Check whether the Contao version is supported
	 *
	 * @return boolean True if the Contao version is not supported
	 */
	public function hasVersionError()
	{
		return !empty($this->errors['version']);
	}

	/**
	 * Return the version error message
	 *
	 * @return array The version error message
	 */
	public function getVersionError()
	{
		return $this->errors['version'];
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
	 * Check whether there was an error retrieving the version file
	 *
	 * @return boolean True if there was an error retrieving the version file
	 */
	public function hasVersionFileError()
	{
		return isset($this->errors['versionFile']);
	}

	/**
	 * Return the version file error
	 *
	 * @return string The version file error
	 */
	public function getVersionFileError()
	{
		return $this->errors['versionFile'];
	}

	/**
	 * Find the constants.php file
	 *
	 * @return boolean True if the constants.php file was found
	 */
	protected function findConstants()
	{
		define('TL_ROOT', 'Required for Contao 2.11');

		if (file_exists(__DIR__ . '/../../system/constants.php')) {
			include __DIR__ . '/../../system/constants.php';
		} elseif (file_exists(__DIR__ . '/../../system/config/constants.php')) {
			include __DIR__ . '/../../system/config/constants.php';
		} elseif (file_exists(__DIR__ . '/../../../vendor/contao/core-bundle/src/Resources/contao/config/constants.php')) {
			include __DIR__ . '/../../../vendor/contao/core-bundle/src/Resources/contao/config/constants.php';
		} elseif (file_exists(__DIR__ . '/../../../vendor/contao/contao/core-bundle/src/Resources/contao/config/constants.php')) {
			include __DIR__ . '/../../../vendor/contao/contao/core-bundle/src/Resources/contao/config/constants.php';
		} else {
			$this->constants = false;

			return false;
		}

		return true;
	}

	/**
	 * Get the name of the version file
	 *
	 * @return string The version file name
	 */
	protected function getVersionFilePath()
	{
		return 'versions/' . VERSION . '.' . BUILD . '.json';
	}

	/**
	 * Get the URL to the version file
	 *
	 * @return string The url to the version file
	 */
	public function getVersionFileUrl()
	{
		return 'https://download.contao.org/' . $this->getVersionFilePath();
	}

	/**
	 * Download the version file
	 *
	 * @return boolean True if the Contao version is supported
	 */
	protected function getVersionFile()
	{
		$file = $this->getVersionFilePath();

		if (!file_exists($file)) {
			try {
				$url = $this->getVersionFileUrl();
				file_put_contents($file, curl($url));

				if (!file_exists($file) || filesize($file) < 1) {
					$this->version = false;

					return false;
				}
			} catch (RuntimeException $e) {
				$this->version = false;
				$this->errors['versionFile'] = $e->getMessage();

				return false;
			}
		}

		return true;
	}

	/**
	 * Validate the installation
	 */
	protected function validate()
	{
		$this->errors = array(
			'missing' => array(),
			'corrupt' => array(),
			'optional' => array()
		);

		// Load the file hashes
		$file = 'versions/' . VERSION . '.' . BUILD . '.json';
		$hashes = json_decode(file_get_contents($file));

		foreach ($hashes as $path=>$md5_file) {
			if ($md5_file == '') {
				continue;
			}

			if (!file_exists(__DIR__ . "/../../$path")) {
				if ($this->isOptional($path)) {
					$this->errors['optional'][] = $path;
				} else {
					$this->valid = false;
					$this->errors['missing'][] = $path;
				}
			} else {
				$buffer = str_replace("\r", '', file_get_contents(__DIR__ . "/../../$path"));

				// Check the MD5 hash
				if (strncmp(md5($buffer), $md5_file, 10) !== 0) {
					$this->valid = false;

					if ($this->isOptional($path)) {
						$this->errors['optional'][] = $path;
					} else {
						$this->errors['corrupt'][] = $path;
					}
				}

				$buffer = null;
			}
		}
	}

	/**
	 * Check if a file is optional
	 *
	 * @param string $path The file path
	 *
	 * @return boolean True if the file is optional
	 */
	protected function isOptional($path)
	{
		if ($path == 'files/tinymce.css' || $path == 'templates/music_academy.sql') {
			return true;
		}

		if (strncmp($path, 'files/tiny_templates/', 11) === 0 || strncmp($path, 'files/music_academy/', 20) === 0) {
			return true;
		}

		return false;
	}
}
