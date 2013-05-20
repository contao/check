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
 * Check the PHP version requirements
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2013
 */
class Index
{

	/**
	 * Execute the command
	 */
	public function run()
	{
		include 'views/index.phtml';
	}


	/**
	 * Return the minimum PHP version required for Contao 2
	 *
	 * @return string The PHP version string
	 */
	public function getContao2Version()
	{
		return '5.2.0';
	}


	/**
	 * Return the minimum PHP version required for Contao 3
	 *
	 * @return string The PHP version string
	 */
	public function getContao3Version()
	{
		return '5.3.2';
	}


	/**
	 * Return true if Contao 2 can be installed
	 *
	 * @return boolean True if Contao 2 can be installed
	 */
	public function supportsContao2()
	{
		return version_compare(phpversion(), $this->getContao2Version(), '>=');
	}


	/**
	 * Return true if Contao 3 can be installed
	 *
	 * @return boolean True if Contao 3 can be installed
	 */
	public function supportsContao3()
	{
		return version_compare(phpversion(), $this->getContao3Version(), '>=');
	}


	/**
	 * Return true if the Extension Repository can be used
	 *
	 * @return boolean True if the Extension Repository can be used
	 */
	protected function canUseRepository()
	{
		include 'repository.php';
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
	 * Return true if the Live Update can be used
	 *
	 * @return boolean True if the Live Update can be used
	 */
	protected function canUseLiveUpdate()
	{
		include 'live-update.php';
		$update = new LiveUpdate;

		if (!$update->hasPhp532()) {
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
	 * Return true if the Safe Mode Hack is not required
	 *
	 * @return boolean True if the Safe Mode Hack is not required
	 */
	protected function requiresSafeModeHack()
	{
		include 'safe-mode-hack.php';
		$smh = new SafeModeHack;

		if ($smh->isEnabled()) {
			return false;
		}

		if (!$smh->canCreateFolder()) {
			return false;
		}

		if (!$smh->canCreateFile()) {
			return false;
		}

		return true;
	}
}
