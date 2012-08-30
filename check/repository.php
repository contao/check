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
 * Class Repository
 * Contains checks to run the extension repository
 */
class Repository
{
	/**
	 * The status of all tests
	 * @var bool
	 */
	protected $blnStatus = true;

	/**
	 * The error message
	 * @var string
	 */
	public $strError = '';

	/**
	 * Explain what went wrong
	 * @var string
	 */
	public $strExplain = '';


	/**
	 * Perform a bunch of checks on the webserver
	 *
	 * @param	void
	 * @return	bool	Return true if the Server can use the ER, false if he can't
	 */
	public function checkServer()
	{
		// perform all tests
		$this->checkSoapExtension();
		$this->checkConnection();

		return $this->blnStatus;
	}

	/**
	 * Check: soap extension
	 * Checks if the soap extension is available.
	 *
	 * @param	void
	 * @return	void
	 */
	protected function checkSoapExtension()
	{
		if (extension_loaded('soap') === false)
		{
			$this->blnStatus = false;

			$this->strError = _('The PHP SOAP extension is not available.');
			$this->strExplain = printf(_('The PHP SOAP extension is required to communicate with the Extension Repository server. You can enable it by compiling PHP with the --enable-soap flag. Depending on your Contao version, you might also be able to use the %s extension.'), '<a href="http://contao.org/extension-list/view/nusoap.html">Nusoap</a>');
		}
	}


	/**
	 * Check: connection
	 * Checks if a connection is possible or if there is trouble with a firewal
	 *
	 * @param	void
	 * @return	void
	 */
	protected function checkConnection()
	{
		// connect to contao.org
		$handleConnection = fsockopen("www.contao.org", 80, $errno, $errstr, 10);

		// check if the connection failed
		if ($handleConnection === false)
		{
			$this->blnStatus = false;

			$this->strError = _('Unable to connect to www.contao.org.');
			$this->strExplain = _('Maybe there is a firewall blocking your reqeust.');
		}

		fclose($handleConnection);
	}
}

$objRepository = new Repository();
$blnStatus = $objRepository->checkServer();


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
    <h1>Contao Check <small><?php echo _('Extension Repository') ?></small></h1>
  </div>
  <div class="row">
    <h2><?php echo _('PHP SOAP extension') ?></h2>
    <?php if ($blnStatus): ?>
      <p class="confirm"><?php echo _('The PHP SOAP extension is available.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo $objRepository->strError; ?></p>
      <p class="explain"><?php echo $objRepository->strExplain; ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if ($blnStatus): ?>
	  <p class="confirm large"><?php echo _('You can use the Extension Repository on this server.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('You cannot use the Extension Repository on this server.') ?></p>
	<?php endif; ?>
  </div>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>