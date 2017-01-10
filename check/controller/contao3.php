<?php

/*
 * Contao check
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Check the Contao 3.x requirements
 *
 * @author Fritz Michael Gschwantner <https://github.com/fritzmg>
 */
class Contao3
{
	const PHP_VERSION = '5.4.0';

	/**
	 * @var boolean
	 */
	protected $compatible = true;

	/**
	 * Execute the command
	 */
	public function run()
	{
		include __DIR__ . '/../views/contao3.phtml';
	}

	/**
	 * Return the Contao 3.x compatibility of the environment
	 *
	 * @return boolean True if Contao 3.x can be run
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
	 * Check whether the PHP GDlib extension is available
	 *
	 * @return boolean True if the PHP GDlib extension is available
	 */
	public function hasGd()
	{
		if (extension_loaded('gd')) {
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
}
