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
 * Validate and existing Contao installation
 * 
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
 */
class Validator
{

	/**
	 * Valid installation
	 * @var boolean
	 */
	protected $valid = true;

	/**
	 * Constants found
	 * @var boolean
	 */
	protected $constants = true;

	/**
	 * Version found
	 * @var boolean
	 */
	protected $version = true;

	/**
	 * Errors
	 * @var array
	 */
	protected $errors = array();


	/**
	 * Check whether there is a Contao installation
	 */
	public function run()
	{
		if (!$this->findConstants() || !$this->checkVersion()) {
			$this->valid = false;
		} else {
			$this->validate();
		}
	}


	/**
	 * Check whether the constants.php file has been found
	 * 
	 * @return boolean True if the constants.php file has been found
	 */
	public function hasConstants()
	{
		return $this->constants;
	}


	/**
	 * Check whether the Contao version is supported
	 * 
	 * @return boolean True if the Contao version is supported
	 */
	public function isSupportedVersion()
	{
		return $this->version;
	}


	/**
	 * Check whether there are missing files
	 * 
	 * @return boolean True if there are missing files
	 */
	public function hasMissing()
	{
		return !empty($this->errors['missing']);
	}


	/**
	 * Return the missing files as array
	 * 
	 * @return array The missing files array
	 */
	public function getMissing()
	{
		return $this->errors['missing'];
	}


	/**
	 * Check whether there are corrupt files
	 * 
	 * @return boolean True if there are corrupt files
	 */
	public function hasCorrupt()
	{
		return !empty($this->errors['corrupt']);
	}


	/**
	 * Return the corrupt files as array
	 * 
	 * @return array The corrupt files array
	 */
	public function getCorrupt()
	{
		return $this->errors['corrupt'];
	}


	/**
	 * Check whether the installation is vaild
	 * 
	 * @return boolean True if the installation is valid
	 */
	public function isValid()
	{
		return $this->valid;
	}


	/**
	 * Find the constants.php file
	 * 
	 * @return boolean True if the constants.php file was found
	 */
	protected function findConstants()
	{
		define('TL_ROOT', dirname(__DIR__));

		if (file_exists("../system/constants.php")) {
			include "../system/constants.php";
		} elseif (file_exists("../system/config/constants.php")) {
			include "../system/config/constants.php";
		} else {
			$this->constants = false;
			return false;
		}

		return true;
	}


	/**
	 * Check whether the Contao version is supported
	 * 
	 * @return boolean True if the Contao version is supported
	 */
	protected function checkVersion()
	{
		$file = 'versions/' . VERSION . '.' . BUILD . '.json';

		if (!file_exists($file)) {
			$this->version = false;
			return false;
		}

		return true;
	}


	/**
	 * Validate the installation
	 */
	protected function validate()
	{
		$this->errors = array('missing'=>array(), 'corrupt'=>array());

		// Load the file hashes
		$file = 'versions/' . VERSION . '.' . BUILD . '.json';
		$hashes = json_decode(file_get_contents($file));

		foreach ($hashes as $info) {
			list($path, $md5_file, $md5_code) = $info;

			if (!$md5_code) {
				$md5_code = $md5_file;
			}

			if (!file_exists('../' . $path)) {
				$this->valid = false;
				$this->errors['missing'][] = $path;
			} else {
				$buffer = str_replace("\r", '', file_get_contents('../' . $path));

				// Check the MD5 hash with and without comments
				if (strncmp(md5($buffer), $md5_file, 10) !== 0) {
					if (strncmp(md5(preg_replace('@/\*.*\*/@Us', '', $buffer)), $md5_code, 10) !== 0) {
						$this->valid = false;
						$this->errors['corrupt'][] = $path;
					}
				}

				$buffer = null;
			}
		}
	}
}

$validator = new Validator;
$validator->run();

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
    <h2><?php echo _('Validate an installation') ?></h2>
  </div>
  <?php if (!$validator->hasConstants()): ?>
    <div class="row">
      <h3><?php echo _('Installation') ?></h3>
      <p class="error"><?php echo _('Could not find a Contao installation.') ?></p>
      <p class="explain"><?php echo _('To validate an existing installation, please upload the "check" folder to your installation directory.') ?></p>
    </div>
  <?php elseif (!$validator->isSupportedVersion()): ?>
    <div class="row">
      <h3><?php echo _('Unknown version') ?></h3>
      <p class="error"><?php printf(_('The installed version %s is not (yet) supported.'), VERSION . '.' . BUILD) ?></p>
      <p class="explain"><?php echo _('There is no version file for your Contao installation. Are you using a stable Contao version and do you have the latest version of the Contao Check?') ?></p>
    </div>
  <?php else: ?>
  <?php if ($validator->hasMissing()): ?>
    <div class="row">
      <h3><?php echo _('Missing files') ?></h3>
      <ul class="validate">
        <?php foreach ($validator->getMissing() as $file): ?>
          <li><?php echo $file ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <?php if ($validator->hasCorrupt()): ?>
    <div class="row">
      <h3><?php echo _('Corrupt files') ?></h3>
      <ul class="validate">
        <?php foreach ($validator->getCorrupt() as $file): ?>
          <li><?php echo $file ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <div class="row">
    <?php if ($validator->isValid()): ?>
	  <p class="confirm large"><?php echo _('Your installation is up to date.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('Your installation is not up to date.') ?></p>
	<?php endif; ?>
  </div>
  <?php endif; ?>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>