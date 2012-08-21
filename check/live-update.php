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

// PHP 5.3
$php_version = phpversion();
$php_version_ok = version_compare($php_version, '5.3.2', '>');

// Phar extension
$phar_ok = extension_loaded('Phar');

// ionCube Loader
$ioncube_ok = !extension_loaded('ionCube Loader');

// Suhosin extension (not the patch!)
$suhosin = ini_get('suhosin.executor.include.whitelist');
$suhosin_ok = ($suhosin === false || stripos($suhosin, 'phar') !== false);

// Detect Unicode
$unicode = ini_get('detect_unicode');
$unicode_ok = ($unicode == '' || $unicode == 0 || $unicode == 'Off');

// FastCGI+eAccelerator
$fast_cgi = (php_sapi_name() == 'cgi-fcgi');
$eaccelerator = extension_loaded('eaccelerator') && ini_get('eaccelerator.enable');
$fast_cgi_ok = (!$fast_cgi && !$eaccelerator); 

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
    <h1>Contao Check <small><?php echo _('Live Update') ?></small></h1>
  </div>
  <div class="row">
    <h2><?php echo _('PHP 5.3.2 or greater') ?></h2>
    <?php if ($php_version_ok): ?>
      <p class="confirm"><?php printf(_('You have PHP version %s.'), $php_version) ?></p>
    <?php else: ?>
      <p class="error"><?php printf(_('You have PHP version %s.'), $php_version) ?></p>
      <p class="explain"><?php printf(_('Phar has been added to the PHP core in version 5.3, so you require at least PHP version 5.3.2 to use .phar files. If you have PHP 5.2, you might be able to use .phar files by installing the PECL phar extension (see %s).'), '<a href="http://pecl.php.net/package/phar">http://pecl.php.net/package/phar</a>') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2><?php echo _('PHP Phar extension') ?></h2>
    <?php if ($phar_ok): ?>
      <p class="confirm"><?php echo _('The PHP Phar extension is enabled.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The PHP Phar extension is not enabled.') ?></p>
      <p class="explain"><?php echo _('The PHP Phar extension is part of the PHP core since PHP 5.3 and has to be explicitly disabled using the --disable-phar flag. Recompile PHP without the flag.') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2>ionCube Loader</h2>
    <?php if ($ioncube_ok): ?>
      <p class="confirm"><?php echo _('The ionCube Loader is not enabled.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The ionCube Loader is enabled.') ?></p>
      <p class="explain"><?php printf(_('At the time being, the ionCube Loader is incompatible with Phar archives. This is a bug in the software which the vendor has to fix. Until then, the only thing you can do is to disable the ionCube Loader. More information is available here: %s'), '<a href="http://forum.ioncube.com/viewtopic.php?p=8867">http://forum.ioncube.com/viewtopic.php?p=8867</a>') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2><?php echo _('Suhosin extension') ?></h2>
    <?php if ($suhosin_ok): ?>
      <p class="confirm"><?php echo _('The Suhosin extension is not installed or correctly configured for .phar files.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The Suhosin extension does not allow to run .phar files.') ?></p>
      <p class="explain"><?php echo _('You have to add "phar" to the list of allowed streams in your php.ini: <code>suhosin.executor.include.whitelist = phar</code>.') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2><?php echo _('Detect Unicode') ?></h2>
    <?php if ($unicode_ok): ?>
      <p class="confirm"><?php echo _('The --enable-zend-multibyte flag is not set or detect_unicode is disabled.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The detect_unicode settings on your server are not correct.') ?></p>
      <p class="explain"><?php printf(_('If PHP is compiled with the --enable-zend-multibyte flag, you have to explicitly disable detect_unicode in your php.ini: <code>detect_unicode = Off</code>. This is a PHP bug which might be fixed in the future. More information is available here: %s'), '<a href="https://bugs.php.net/bug.php?id=53199">https://bugs.php.net/bug.php?id=53199</a>') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2>FastCGI+eAccelerator</h2>
    <?php if ($fast_cgi_ok): ?>
      <p class="confirm"><?php echo _('You are not using FastCGI and eAccelerator.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('You are using FastCGI and eAccelerator.') ?></p>
      <p class="explain"><?php echo _('It seems that FastCGI in combination with the eAccelerator extension is buggy when it comes to Phar archives. You can either disable the eAccelerator extension or use a different PHP cache instead (e.g. FastCGI+APC).') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if ($php_version_ok && $phar_ok && $ioncube_ok && $suhosin_ok && $unicode_ok && $fast_cgi_ok): ?>
	  <p class="confirm large"><?php echo _('You can use the Live Update on this server.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('You cannot use the Live Update on this server.') ?></p>
	<?php endif; ?>
  </div>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>