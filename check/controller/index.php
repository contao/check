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
 * Check the PHP version requirements
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2013-2014
 */
class Index
{

	/**
	 * Safe Mode Hack
	 * @var boolean
	 */
	protected $safeModeHack;


	/**
	 * Execute the command
	 */
	public function run()
	{
		$this->safeModeHack = $this->checkSafeModeHack();

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
	 * Return true if the Safe Mode Hack is required
	 *
	 * @return boolean True if the Safe Mode Hack is required
	 */
	public function requiresSafeModeHack()
	{
		return $this->safeModeHack;
	}


	/**
	 * Return true if the Live Update can be used
	 *
	 * @return boolean True if the Live Update can be used
	 */
	public function canUseLiveUpdate()
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
	 * Return true if the Extension Repository can be used
	 *
	 * @return boolean True if the Extension Repository can be used
	 */
	public function canUseComposer()
	{
		include 'composer.php';
		$composer = new Composer;

		if (!$composer->hasPhp532()) {
			return false;
		}

		if (!$composer->hasMemoryLimit()) {
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

		if ($this->safeModeHack) {
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
	 * Return true if the Safe Mode Hack is required
	 *
	 * @return boolean True if the Safe Mode Hack is required
	 */
	protected function checkSafeModeHack()
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
}
