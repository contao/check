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
 * Check if the PHP process is allowed to create files
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FilePermissions
{
	/**
	 * @var string
	 */
	protected $folderOwner;

	/**
	 * @var string
	 */
	protected $testFolderOwner;

	/**
	 * @var integer
	 */
	protected $testFolderChmod;

	/**
	 * @var string
	 */
	protected $fileOwner;

	/**
	 * @var string
	 */
	protected $testFileOwner;

	/**
	 * @var integer
	 */
	protected $testFileChmod;

	/**
	 * @var boolean
	 */
	protected $failure = false;

	/**
	 * Execute the command
	 */
	public function run()
	{
		include __DIR__ . '/../views/file-permissions.phtml';
	}

	/**
	 * Return true if the PHP process could not create the file
	 *
	 * @return boolean True if the PHP process could not create the file
	 */
	public function failed()
	{
		return $this->failure;
	}

	/**
	 * Check whether the PHP safe_mode is enabled
	 *
	 * @return boolean True if the PHP safe_mode is enabled
	 */
	public function hasSafeMode()
	{
		$safe_mode = ini_get('safe_mode');

		if ($safe_mode == '' || $safe_mode == 0 || $safe_mode == 'Off') {
			return false;
		}

		$this->failure = true;

		return true;
	}

	/**
	 * Return the owner of the "check" folder
	 *
	 * @return string The owner name
	 */
	public function getFolderOwner()
	{
		return $this->folderOwner['name'];
	}

	/**
	 * Return the owner of the "test" folder
	 *
	 * @return string The owner name
	 */
	public function getTestFolderOwner()
	{
		return $this->testFolderOwner['name'];
	}

	/**
	 * Return the permissions of the "test" folder
	 *
	 * @return integer The CHMOD settings
	 */
	public function getTestFolderChmod()
	{
		return $this->testFolderChmod;
	}

	/**
	 * Check whether PHP is allowed to create folders
	 *
	 * @return boolean True if PHP is allowed create folders
	 */
	public function canCreateFolder()
	{
		$this->folderOwner = posix_getpwuid(@fileowner(dirname(__FILE__)));

		// Try to create a folder
		if (@mkdir('test') !== false) {
			$options = defined('PHP_WINDOWS_VERSION_BUILD') ? array(777) : array(775, 755, 770, 750, 705);

			// Check the folder permissions
			clearstatcache();
			$this->testFolderChmod = decoct(@fileperms('test') & 511);
			$this->testFolderOwner = posix_getpwuid(@fileowner('test'));

			// Check the folder owner
			if (in_array($this->testFolderChmod, $options)) {
				if ($this->folderOwner['name'] == $this->testFolderOwner['name']) {
					@rmdir('test');

					return true;
				}
			}
		}

		@rmdir('test');
		$this->failure = true;

		return false;
	}

	/**
	 * Return the owner of the "check/safe-mode-hack.php" file
	 *
	 * @return string The owner name
	 */
	public function getFileOwner()
	{
		return $this->fileOwner['name'];
	}

	/**
	 * Return the owner of the "test.txt" file
	 *
	 * @return string The owner name
	 */
	public function getTestFileOwner()
	{
		return $this->testFileOwner['name'];
	}

	/**
	 * Return the permissions of the "test.txt" file
	 *
	 * @return integer The CHMOD settings
	 */
	public function getTestFileChmod()
	{
		return $this->testFileChmod;
	}

	/**
	 * Check whether PHP is allowed to create files
	 *
	 * @return boolean True if PHP is allowed create files
	 */
	public function canCreateFile()
	{
		$this->fileOwner = posix_getpwuid(@fileowner(__FILE__));

		// Try to create a file
		if (@file_put_contents('test.txt', '') !== false) {
			$options = defined('PHP_WINDOWS_VERSION_BUILD') ? array(666) : array(664, 644, 660, 640, 604);

			// Check the file permissions
			clearstatcache();
			$this->testFileChmod = decoct(@fileperms('test.txt') & 511);
			$this->testFileOwner = posix_getpwuid(@fileowner('test.txt'));

			// Check the file owner
			if (in_array($this->testFileChmod, $options)) {
				if ($this->fileOwner['name'] == $this->testFileOwner['name']) {
					@unlink('test.txt');

					return true;
				}
			}
		}

		@unlink('test.txt');
		$this->failure = true;

		return false;
	}
}
