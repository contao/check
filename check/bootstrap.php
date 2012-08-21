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

// Hide E_NOTICE
error_reporting(E_ALL^E_NOTICE);

// Start the session
session_start();

// Determine the user language
if (isset($_GET['lng'])) {
	if (preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $_GET['lng'])) {
		$locale = $_GET['lng'];
		$_SESSION['C_LANGUAGE'] = $_GET['lng'];
	}
} elseif (isset($_SESSION['C_LANGUAGE'])) {
	$locale = $_SESSION['C_LANGUAGE'];
} else {
	$accepted = array();
	$languages = explode(',', strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']));
	$locales = scandir('locale');

	for ($i=0; $i<8; $i++) {
		$tag = substr($languages[$i], 0, 2);
		if ($tag != '' && preg_match('/^[a-z]{2}$/', $tag)) {
			$matches = array_values(preg_grep("/^$tag/", $locales));
			if (!empty($matches)) {
				$locale = $matches[0];
				$_SESSION['C_LANGUAGE'] = $matches[0];
				break;
			}
		}
	}
}

// Fall back to English
if (!$locale) {
	$locale = 'en_GB';
	$_SESSION['C_LANGUAGE'] = 'en_GB';
}

// Set the locale
if (preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $locale)) {
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain('messages', __DIR__ . '/locale');
	textdomain('messages');
	bind_textdomain_codeset('messages', 'UTF-8');
} else {
	die("Unknown locale $locale");
}
