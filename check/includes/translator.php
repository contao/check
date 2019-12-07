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
 * Handle the translations
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Translator
{
	/**
	 * @var array
	 */
	private static $labels = array();

	/**
	 * Load a translation
	 *
	 * @param string $locale The locale
	 */
	public static function load($locale)
	{
		$path = __DIR__ . '/../i18n/' . $locale . '.php';

		// Fall back to English
		if (!file_exists($path)) {
			$path = __DIR__ . '/../i18n/en.php';
		}

		self::$labels = include $path;
	}

	/**
	 * Translate a string
	 *
	 * @param string $str The input string
	 *
	 * @return string The translated string
	 */
	public static function translate($str)
	{
		if (isset(self::$labels[$str])) {
			return self::$labels[$str];
		}

		return $str;
	}
}
