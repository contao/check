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


/**
 * Class Bootstrap
 * Contain methods to boot the contao check
 */
class Bootstrap
{
	/**
	 * Kickstart the contao check
	 *
	 * @param	void
	 * @return	void
	 */
	public function kickstart()
	{
		$this->setPhpOptions();
		$this->determineUserLanguage();
	}


	/**
	 * Set some PHP options
	 *
	 * @param	void
	 * @return	void
	 */
	protected function setPhpOptions()
	{
		// enable error reporting, but hide notices
		error_reporting(E_ALL^E_NOTICE);
		ini_set('display_errors', '1');

		// start the session
		session_start();
	}


	/**
	 * Determine the user language based on
	 * the HTTP_ACCEPT_LANGUAGE or the GET param lng
	 *
	 * @param	void
	 * @return	void
	 */
	protected function determineUserLanguage()
	{
		// create some variables
		$strLocale = '';
		$strLng = filter_input(INPUT_GET, 'lng', FILTER_SANITIZE_STRING);

		// check if the user forces a language with the $_GET param lng
		if (isset($_GET['lng']))
		{
			// check if the value is a valid language identifier. Example: en_US
			if ($this->isValidLocale($strLng))
			{
				// the user has requested a valid language
				$strLocale = $strLng;

				// storing this information in the session
				$_SESSION['C_LANGUAGE'] = $strLng;
			}
		}

		// if there is no language request, check if we already have one in the session
		elseif (isset($_SESSION['C_LANGUAGE']))
		{
			$strLocale = $_SESSION['C_LANGUAGE'];
		}

		// nope, no language requested. Now we try to use one based on the HTTP request
		else
		{
			// get some values
			$arrRequestedLanguages = $this->getRequestedLanguages();
			$arrAvailableLanguages = $this->getAvailableLanguages();


			// check if one of the requested languages is available
			foreach ($arrRequestedLanguages as $v)
			{
				if (in_array($v, $arrAvailableLanguages))
				{
					$strLocale = $v;
					$_SESSION['C_LANGUAGE'] = $v;

					// first match wins, so we break out of the foreach
					break;
				}
			}
		}


		// if there is noting valid, we use en_US like evey
		// other developer in the world. Fuck british!
		if ($strLocale == '')
		{
			$strLocale = 'en_US';
			$_SESSION['C_LANGUAGE'] = 'en_US';
		}

		// set the locales and kickstart gettext
		$this->setLocale($strLocale);
	}


	/**
	 * Set the determined locale and enable the gettext support
	 *
	 * @param	string	$strLocale	The locale witch should be used
	 * @return	void
	 * @throws	Exception			Throws an exception if there is no translation available
	 */
	protected function setLocale($strLocale)
	{
		// check if the given locale is valid
		if ($this->isValidLocale($strLocale))
		{
			// set some environment vars and bind gettext
			putenv('LC_ALL=' . $strLocale);
			setlocale(LC_ALL, $strLocale);
			bindtextdomain('messages', __DIR__ . '/locale');
			textdomain('messages');
			bind_textdomain_codeset('messages', 'UTF-8');

			return;
		}

		throw new Exception(sprintf('Unknown locale %s', $strLocale), 1);
	}


	/**
	 * Check if the given locale is valid
	 * Example: en_US
	 *
	 * @param	string	$strLocale	The locale you want to check
	 * @return	bool				Return true if it's a valid locale, otherwize false
	 */
	protected function isValidLocale($strLocale)
	{
		if (preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $strLocale))
		{
			return true;
		}

		return false;
	}


	/**
	 * Return all available translations as array
	 *
	 * @param	void
	 * @return	array	An array witch all available translations
	 */
	protected function getAvailableLanguages()
	{
		$arrFileSystem = glob('locale/[a-z][a-z]_[A-Z][A-Z]*', GLOB_ONLYDIR);
		$arrLanguages = array();

		// sadly we have to remove the directory path from every entry
		foreach ($arrFileSystem as $v)
		{
			$arrLanguages[] = str_replace('locale/', '', $v);
		}

		return $arrLanguages;
	}


	/**
	 * Return all requested langugages as an array. All q factors and
	 * non valid locales are removed from the array
	 *
	 * @param	void
	 * @return	array	Return an array with all
	 */
	protected function getRequestedLanguages()
	{
		$arrReturn = array();

		// split all accept languages from there q factors
		// NOTE: different than the original implementation, this also works with .jp browsers
		preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $arrHttpLangs);

		// remove all invalid locales from the array
		foreach ($arrHttpLangs[1] as $v)
		{
			$arrChunks = explode('-', $v);
			$strLocale = $arrChunks[0] . '_' . strtoupper($arrChunks[1]);

			if ($this->isValidLocale($strLocale))
			{
				$arrReturn[] = $strLocale;
			}
		}

		return $arrReturn;
	}
}


// create the Bootstrap object and kickstart the contao check
$objBootstrap = new Bootstrap();
$objBootstrap->kickstart();

