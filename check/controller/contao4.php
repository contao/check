<?php

/*
 * Contao check
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Check the Contao 4.x requirements
 *
 * @author Fritz Michael Gschwantner <https://github.com/fritzmg>
 */
class Contao4
{
	const PHP_VERSION = '5.6.0';

	/**
	 * @var boolean
	 */
	protected $compatible = true;

	/**
	 * Execute the command
	 */
	public function run()
	{
		include __DIR__ . '/../views/contao4.phtml';
	}

	/**
	 * Return the Contao 4.x compatibility of the environment
	 *
	 * @return boolean True if Contao 4.x can be run
	 */
	public function isCompatible()
	{
		return $this->compatible;
	}

	/**
	 * Check whether the PHP version meets the requirements
	 *
	 * @return boolean True if the PHP version meets the requirements
	 */
	public function hasPhp()
	{
		if (version_compare(phpversion(), static::PHP_VERSION, '>=')) {
			return true;
		}

		$this->compatible = false;

		return false;
	}

	/**
	 * Check whether any of the supported graphics libraries are available
	 *
	 * @return boolean True if any of the supported graphics libraries are available
	 */
	public function hasGraphicsLib()
	{
		if (function_exists('gd_info')) {
			 if (version_compare(GD_VERSION, '2.0.1', '>')) {
			 	return true;
			 }
		}

		if (class_exists('Imagick')) {
			return true;
		}

		if (class_exists('Gmagick')) {
			return true;
		}

		$this->compatible = false;

		return false;
	}

	/**
	 * Check whether the PHP DOM extension is available
	 *
	 * @return boolean True if the PHP DOM extension is available
	 */
	public function hasDom()
	{
		if (extension_loaded('dom')) {
			return true;
		}

		$this->compatible = false;

		return false;
	}

	/**
	 * Check whether the PHP intl extension is available
	 *
	 * @return boolean True if the PHP intl extension is available
	 */
	public function hasIntl()
	{
		if (extension_loaded('intl')) {
			return true;
		}

		$this->compatible = false;

		return false;
	}

	/**
	 * Check whether the system tmp directory is writeable
	 *
	 * @return boolean True if the system tmp directory is writeable
	 */
	public function canWriteTmpDir()
	{
		if (is_writable(sys_get_temp_dir())) {
			return true;
		}

		$this->compatible = false;

		return false;
	}

	/**
	 * Check whether the PHP symlink function is available
	 *
	 * @return boolean True if the PHP symlink function is available
	 */
	public function canUseSymlink()
	{
		if (function_exists('symlink')) {
			return true;
		}

		$this->compatible = false;

		return false;
	}
}
