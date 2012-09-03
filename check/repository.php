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
 * Check the Extension Repository requirements
 * 
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
 */
class Repository
{

	/**
	 * Availability
	 * @var boolean
	 */
	protected $available = true;


	/**
	 * Return the availability of the Extension Repository
	 * 
	 * @return boolean True if the Extension Repository can be used
	 */
	public function isAvailable()
	{
		return $this->available;
	}


	/**
	 * Check whether the PHP SOAP extension is available
	 * 
	 * @return boolean True if the PHP SOAP extension is available
	 */
	public function hasSoap()
	{
		if (extension_loaded('soap')) {
			return true;
		}

		$this->available = false;
		return false;
	}


	/**
	 * Check whether a connection can be established
	 */
	public function canConnect()
	{
		$connection = fsockopen("contao.org", 80, $errno, $errstr, 10);
		$connected = ($connection !== false);
		fclose($connection);

		if ($connected) {
			return true;
		}

		$this->available = false;
		return false;
	}
}

$repository = new Repository();


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
    <h2><?php echo _('Extension Repository') ?></h2>
  </div>
  <div class="row">
    <h3><?php echo _('PHP SOAP extension') ?></h3>
    <?php if ($repository->hasSoap()): ?>
      <p class="confirm"><?php echo _('The PHP SOAP extension is available.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The PHP SOAP extension is not available.') ?></p>
      <p class="explain"><?php printf(_('The PHP SOAP extension is required to communicate with the Extension Repository server. You can enable it by compiling PHP with the --enable-soap flag. Depending on your Contao version, you might also be able to use the %s extension.'), '<a href="http://contao.org/extension-list/view/nusoap.html">Nusoap</a>') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h3><?php echo _('Connection test') ?></h3>
    <?php if ($repository->canConnect()): ?>
      <p class="confirm"><?php echo _('A connection to contao.org could be established.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('A connection to contao.org could not be established.') ?></p>
      <p class="explain"><?php echo _('Maybe the request has been blocked by a firewall. Please contact your server administrator.') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if ($repository->isAvailable()): ?>
	  <p class="confirm large"><?php echo _('You can use the Extension Repository on this server.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('You cannot use the Extension Repository on this server.') ?></p>
	<?php endif; ?>
  </div>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>