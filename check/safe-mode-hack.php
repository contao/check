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

require 'bootstrap.php';
define('IS_WINDOWS', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));


/**
 * Check the Safe Mode Hack requirements
 * 
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
 */
class SafeModeHack
{

	/**
	 * Folder owner
	 * @var string
	 */
	protected $folderOwner;

	/**
	 * Test folder owner
	 * @var string
	 */
	protected $testFolderOwner;

	/**
	 * Test folder permissions
	 * @var integer
	 */
	protected $testFolderChmod;

	/**
	 * File owner
	 * @var string
	 */
	protected $fileOwner;

	/**
	 * Test file owner
	 * @var string
	 */
	protected $testFileOwner;

	/**
	 * Test file permissions
	 * @var integer
	 */
	protected $testFileChmod;

	/**
	 * Required
	 * @var boolean
	 */
	protected $required = false;


	/**
	 * Return whether the Safe Mode Hack is required
	 * 
	 * @return boolean True if the Safe Mode Hack is required
	 */
	public function isRequired()
	{
		return $this->required;
	}


	/**
	 * Check whether the PHP safe_mode is enabled
	 * 
	 * @return boolean True if the PHP safe_mode is enabled
	 */
	public function isEnabled()
	{
		$safe_mode = ini_get('safe_mode');

		if ($safe_mode == '' || $safe_mode == 0 || $safe_mode == 'Off') {
			return false;
		}

		$this->required = true;
		return true;
	}


	/**
	 * Return the owner of the "check" folder
	 * 
	 * @return string The owner name
	 */
	public function getFolderOwner()
	{
		return $this->folderOwner;
	}


	/**
	 * Return the owner of the "test" folder
	 * 
	 * @return string The owner name
	 */
	public function getTestFolderOwner()
	{
		return $this->testFolderOwner;
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
		$this->folderOwner = $this->getpwuid(@fileowner(__DIR__));

		// Try to create a folder
		if (@mkdir('test') !== false) {
			$options = IS_WINDOWS ? array(777) : array(775, 755, 750);

			// Check the folder permissions
			clearstatcache();
			$this->testFolderChmod = decoct(@fileperms('test') & 511);
			$this->testFolderOwner = $this->getpwuid(@fileowner('test'));

			// Check the folder owner
			if (in_array($this->testFolderChmod, $options)) {
				if ($this->folderOwner == $this->testFolderOwner) {
					@rmdir('test');
					return true;
				}
			}
		}

		@rmdir('test');
		$this->required = true;

		return false;
	}


	/**
	 * Return the owner of the "check/safe-mode-hack.php" file
	 * 
	 * @return string The owner name
	 */
	public function getFileOwner()
	{
		return $this->fileOwner;
	}


	/**
	 * Return the owner of the "test.txt" file
	 * 
	 * @return string The owner name
	 */
	public function getTestFileOwner()
	{
		return $this->testFileOwner;
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
		$this->fileOwner = $this->getpwuid(@fileowner(__FILE__));

		// Try to create a file
		if (@file_put_contents('test.txt', '') !== false) {
			$options = IS_WINDOWS ? array(666) : array(664, 644, 660, 640);

			// Check the file permissions
			clearstatcache();
			$this->testFileChmod = decoct(@fileperms('test.txt') & 511);
			$this->testFileOwner = $this->getpwuid(@fileowner('test.txt'));

			// Check the file owner
			if (in_array($this->testFileChmod, $options)) {
				if ($this->fileOwner == $this->testFileOwner) {
					@unlink('test.txt');
					return true;
				}
			}
		}

		@unlink('test.txt');
		$this->required = true;

		return false;
	}


	/**
	 * Return the user name
	 * 
	 * @param integer $int The user ID
	 * 
	 * @return string The user name
	 */
	protected function getpwuid($int)
	{
		if (!function_exists('posix_getpwuid')) {
			return $int;
		} else {
			$array = posix_getpwuid($int);
			return $array['name'];
		}
	}
}

$smh = new SafeModeHack;

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Contao Check</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div id="wrapper">
  <div class="row">
    <h1>Contao Check <small><?php echo _('Safe Mode Hack') ?></small></h1>
  </div>
  <div class="row">
    <h2><?php echo _('php.ini settings') ?></h2>
    <?php if (!$smh->isEnabled()): ?>
      <p class="confirm"><?php echo _('The PHP safe_mode is not enabled.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The PHP safe_mode is enabled.') ?></p>
      <p class="explain"><?php echo _('It is recommended to disable the safe_mode in your php.ini or server control panel, otherwise you will have to use the Contao Safe Mode Hack to create or manipulate files. Using the Safe Mode Hack will have a negative impact on the performance of your website.') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2><?php echo _('Creating a test folder') ?></h2>
    <?php if ($smh->canCreateFolder()): ?>
      <p class="confirm"><?php printf(_('The test folder could be created (owner: %s, chmod: %s).'), $smh->getFolderOwner(), $smh->getTestFolderChmod()) ?></p>
    <?php elseif ($smh->getTestFolderChmod() === null): ?>
      <p class="error"><?php echo _('The test folder could not be created.') ?></p>
      <p class="explain"><?php echo _('It seems that the PHP process does not have enough permissions to create folders on your server.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The test folder does not have the correct owner or chmod settings.') ?></p>
      <p class="explain"><?php printf(_('The test folder is owned by %s (should be %s) and has the chmod settings %s (should be %s).'), $smh->getTestFolderOwner(), $smh->getFolderOwner(), $smh->getTestFolderChmod(), (IS_WINDOWS ? '777' : _('775, 755 or 750'))) ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2><?php echo _('Creating a test file') ?></h2>
    <?php if ($smh->canCreateFile()): ?>
      <p class="confirm"><?php printf(_('The test file could be created (owner: %s, chmod: %s).'), $smh->getFileOwner(), $smh->getTestFileChmod()) ?></p>
    <?php elseif ($smh->getTestFileChmod() === null): ?>
      <p class="error"><?php echo _('The test file could not be created.') ?></p>
      <p class="explain"><?php echo _('It seems that the PHP process does not have enough permissions to create files on your server.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The test file does not have the correct owner or chmod settings.') ?></p>
      <p class="explain"><?php printf(_('The test file is owned by %s (should be %s) and has the chmod settings %s (should be %s).'), $smh->getTestFileOwner(), $smh->getFileOwner(), $smh->getTestFileChmod(), (IS_WINDOWS ? '666' : _('664, 644, 660 or 640'))) ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if (!$smh->isRequired()): ?>
	  <p class="confirm large"><?php echo _('You do not need the Safe Mode Hack on this server.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('You do need the Safe Mode Hack on this server.') ?></p>
	<?php endif; ?>
  </div>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>