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
 * Check the PHP version requirements
 * 
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
 */
class PhpVersion
{

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
}

$version = new PhpVersion();

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
	<h2><?php echo _('Overview') ?></h2>
  </div>
  <div class="row">
    <h3>PHP <?php echo phpversion() ?></h3>
    <ul>
      <?php if ($version->supportsContao2()): ?>
        <li class="confirm"><?php printf(_('You can install Contao 2.x (requires PHP %s+)'), $version->getContao2Version()) ?></li>
      <?php else: ?>
        <li class="error"><?php printf(_('You cannot install Contao 2.x (requires PHP %s+)'), $version->getContao2Version()) ?></li>
      <?php endif; ?>
      <?php if ($version->supportsContao3()): ?>
        <li class="confirm"><?php printf(_('You can install Contao 3.x (requires PHP %s+)'), $version->getContao3Version()) ?></li>
      <?php else: ?>
        <li class="error"><?php printf(_('You cannot install Contao 3.x (requires PHP %s+)'), $version->getContao3Version()) ?></li>
      <?php endif; ?>
    </ul>
  </div>
  <div class="row">
    <h3><?php echo _('Requirements') ?></h3>
    <ul>
      <li><a href="repository.php"><?php echo _('Can I use the Extension Repository?') ?></a></li>
      <li><a href="live-update.php"><?php echo _('Can I use the Live Update Service?') ?></a></li>
      <li><a href="safe-mode-hack.php"><?php echo _('Do I need to use the Safe Mode Hack?') ?></a></li>
    </ul>
  </div>
  <div class="row">
    <h3><?php echo _('Installation') ?></h3>
    <ul>
      <li><a href="install.php"><?php echo _('Install the latest Contao version') ?></a></li>
      <li><a href="validate.php"><?php echo _('Validate an existing Contao installation') ?></a></li>
    </ul>
    <p class="explain mt"><?php printf(_('Contao Check %s | Contao Open Source CMS system requirements | More information at %s'), CONTAO_CHECK_VERSION, '<a href="http://contao.org">contao.org</a>') ?></p>
  </div>
</div>
</body>
</html>