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


/**
 * Check the Live Update requirements
 * 
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
 */
class LiveUpdate
{

	/**
	 * Availability
	 * @var boolean
	 */
	protected $available = true;


	/**
	 * Return the availability of the Live Update
	 * 
	 * @return boolean True if the Live Update can be used
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
	 * Check whether the PHP Phar extension is available
	 * 
	 * @return boolean True if the PHP Phar extension is available
	 */
	public function hasPhar()
	{
		if (extension_loaded('Phar')) {
			return true;
		}

		$this->available = false;
		return false;
	}


	/**
	 * Check whether the ionCube Loader is enabled
	 * 
	 * @return boolean True if the ionCube Loader is enabled
	 */
	public function hasIonCube()
	{
		if (!extension_loaded('ionCube Loader')) {
			return false;
		}

		// The issues have been fixed in version 4.0.9
		if (version_compare(ioncube_loader_version(), '4.0.9', '>=')) {
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

		if ($suhosin === false || stripos($suhosin, 'phar') !== false) {
			return false;
		}

		$this->available = false;
		return true;
	}


	/**
	 * Check whether detect_unicode is enabled
	 * 
	 * @return boolean True if detect_unicode is enabled
	 */
	public function hasDetectUnicode()
	{
		$unicode = ini_get('detect_unicode');

		if ($unicode == '' || $unicode == 0 || $unicode == 'Off') {
			return false;
		}

		$this->available = false;
		return true;
	}


	/**
	 * Check whether PHP is run as FastCGI with the eAccelerator
	 * 
	 * @return boolean True if PHP is run as FastCGI with the eAccelerator
	 */
	public function isFastCgiEaccelerator()
	{
		$fast_cgi = (php_sapi_name() == 'cgi-fcgi');
		$eaccelerator = extension_loaded('eaccelerator') && ini_get('eaccelerator.enable');

		if (!$fast_cgi && !$eaccelerator) {
			return false;
		}

		$this->available = false;
		return true;
	}
}

$update = new LiveUpdate();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Contao Check <?php echo CONTAO_CHECK_VERSION ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div id="wrapper">
  <h1>Contao Check</h1>
  <div class="row">
    <h2><?php echo _('Live Update') ?></h2>
  </div>
  <div class="row">
    <h3><?php echo _('PHP 5.3.2 or greater') ?></h3>
    <?php if ($update->hasPhp532()): ?>
      <p class="confirm"><?php printf(_('You have PHP version %s.'), phpversion()) ?></p>
    <?php else: ?>
      <p class="error"><?php printf(_('You have PHP version %s.'), phpversion()) ?></p>
      <p class="explain"><?php printf(_('Phar has been added to the PHP core in version 5.3, so you require at least PHP version 5.3.2 to use .phar files. If you have PHP 5.2, you might be able to use .phar files by installing the PECL phar extension (see %s).'), '<a href="http://pecl.php.net/package/phar">http://pecl.php.net/package/phar</a>') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h3><?php echo _('PHP Phar extension') ?></h3>
    <?php if ($update->hasPhar()): ?>
      <p class="confirm"><?php echo _('The PHP Phar extension is enabled.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The PHP Phar extension is not enabled.') ?></p>
      <p class="explain"><?php echo _('The PHP Phar extension is part of the PHP core since PHP 5.3 and has to be explicitly disabled using the --disable-phar flag. Recompile PHP without the flag.') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h3>ionCube Loader</h3>
    <?php if (!$update->hasIonCube()): ?>
      <p class="confirm"><?php echo _('The ionCube Loader is not enabled or at least at version 4.0.9.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('An old version of the ionCube Loader prior to version 4.0.9 is installed.') ?></p>
      <p class="explain"><?php printf(_('Before version 4.0.9, the ionCube Loader was incompatible with Phar archives. Either upgrade to the latest version or disable the module. More information is available here: %s'), '<a href="http://forum.ioncube.com/viewtopic.php?p=8867">http://forum.ioncube.com/viewtopic.php?p=8867</a>') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h3><?php echo _('Suhosin extension') ?></h3>
    <?php if (!$update->hasSuhosin()): ?>
      <p class="confirm"><?php echo _('The Suhosin extension is not installed or correctly configured for .phar files.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The Suhosin extension does not allow to run .phar files.') ?></p>
      <p class="explain"><?php echo _('You have to add "phar" to the list of allowed streams in your php.ini: <code>suhosin.executor.include.whitelist = phar</code>.') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h3><?php echo _('Detect Unicode') ?></h3>
    <?php if (!$update->hasDetectUnicode()): ?>
      <p class="confirm"><?php echo _('The --enable-zend-multibyte flag is not set or detect_unicode is disabled.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The detect_unicode settings on your server are not correct.') ?></p>
      <p class="explain"><?php printf(_('If PHP is compiled with the --enable-zend-multibyte flag, you have to explicitly disable detect_unicode in your php.ini: <code>detect_unicode = Off</code>. This is a PHP bug which might be fixed in the future. More information is available here: %s'), '<a href="https://bugs.php.net/bug.php?id=53199">https://bugs.php.net/bug.php?id=53199</a>') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h3>FastCGI+eAccelerator</h3>
    <?php if (!$update->isFastCgiEaccelerator()): ?>
      <p class="confirm"><?php echo _('You are not using FastCGI and eAccelerator.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('You are using FastCGI and eAccelerator.') ?></p>
      <p class="explain"><?php echo _('It seems that FastCGI in combination with the eAccelerator extension is buggy when it comes to Phar archives. You can either disable the eAccelerator extension or use a different PHP cache instead (e.g. FastCGI+APC).') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if ($update->isAvailable()): ?>
	  <p class="confirm large"><?php echo _('You can use the Live Update on this server.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('You cannot use the Live Update on this server.') ?></p>
	<?php endif; ?>
  </div>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>