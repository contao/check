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

require_once 'composer.php';
require_once 'file-permissions.php';
require_once 'live-update.php';
require_once 'repository.php';


/**
 * Check the PHP version requirements
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2013-2014
 */
class Index
{
	const CONTAO2_VERSION = '5.2.0';
	const CONTAO3_VERSION = '5.3.7';
	const CONTAO4_VERSION = '5.4.0';


	/**
	 * File permissions
	 * @var boolean
	 */
	protected $filePermissions;


	/**
	 * Execute the command
	 */
	public function run()
	{
		$this->filePermissions = $this->checkFilePermissions();

		include 'views/index.phtml';
	}


	/**
	 * Return true if Contao 2 can be installed
	 *
	 * @return boolean True if Contao 2 can be installed
	 */
	public function supportsContao2()
	{
		return version_compare(phpversion(), static::CONTAO2_VERSION, '>=');
	}


	/**
	 * Return true if Contao 3 can be installed
	 *
	 * @return boolean True if Contao 3 can be installed
	 */
	public function supportsContao3()
	{
		return version_compare(phpversion(), static::CONTAO3_VERSION, '>=');
	}


	/**
	 * Return true if Contao 4 can be installed
	 *
	 * @return boolean True if Contao 4 can be installed
	 */
	public function supportsContao4()
	{
		return version_compare(phpversion(), static::CONTAO4_VERSION, '>=');
	}


	/**
	 * Return true if the PHP process is allowed to create files
	 *
	 * @return boolean True if the PHP process is allowed to create files
	 */
	public function canCreateFiles()
	{
		return $this->filePermissions;
	}


	/**
	 * Return true if the Live Update can be used
	 *
	 * @return boolean True if the Live Update can be used
	 */
	public function canUseLiveUpdate()
	{
		$update = new LiveUpdate;

		if (!$update->hasPhp()) {
			return false;
		}

		if (!$update->hasPhar()) {
			return false;
		}

		if (!$update->hasSsl()) {
			return false;
		}

		if ($update->hasIonCube()) {
			return false;
		}

		if ($update->hasSuhosin()) {
			return false;
		}

		if ($update->hasDetectUnicode()) {
			return false;
		}

		if ($update->isFastCgiEaccelerator()) {
			return false;
		}

		if (!$update->canConnect()) {
			return false;
		}

		return true;
	}


	/**
	 * Return true if the Extension Repository can be used
	 *
	 * @return boolean True if the Extension Repository can be used
	 */
	public function canUseComposer()
	{
		$composer = new Composer;

		if (!$composer->hasPhp()) {
			return false;
		}

		if (!$composer->hasCurl()) {
			return false;
		}

		if ($composer->hasApc()) {
			return false;
		}

		if ($composer->hasSuhosin()) {
			return false;
		}

		if (!$composer->hasAllowUrlFopen()) {
			return false;
		}

		if ($this->filePermissions) {
			return false;
		}

		return true;
	}


	/**
	 * Return true if the Extension Repository can be used
	 *
	 * @return boolean True if the Extension Repository can be used
	 */
	public function canUseRepository()
	{
		$repository = new Repository;

		if (!$repository->hasSoap()) {
			return false;
		}

		if (!$repository->canConnect()) {
			return false;
		}

		return true;
	}


	/**
	 * Return true if the PHP process is allowed to create files
	 *
	 * @return boolean True if the PHP process is allowed to create files
	 */
	protected function checkFilePermissions()
	{
		$permissions = new FilePermissions;

		if ($permissions->hasSafeMode()) {
			return true;
		}

		if (!$permissions->canCreateFolder()) {
			return true;
		}

		if (!$permissions->canCreateFile()) {
			return true;
		}

		return false;
	}
}
