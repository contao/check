<?php

/*
 * Contao check
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

// Add the posix_getpwuid function
if (!function_exists('posix_getpwuid') && !function_disabled('posix_getpwuid')) {
	function posix_getpwuid($int) {
		return array('name'=>$int);
	}
}

/**
 * Translate a string
 *
 * @param string $str
 *
 * @return string The translated string
 */
function __($str)
{
	return Translator::translate($str);
}

/**
 * Check if a function is disabled
 *
 * @param string $func
 *
 * @return boolean True if the function is disabled
 */
function function_disabled($func)
{
	return in_array($func, array_map('trim', explode(',', ini_get('disable_functions'))));
}

/**
 * Retrieve information using cURL
 *
 * @param string $url The URL
 *
 * @return string The output string
 *
 * @throws RuntimeException If the download fails
 */
function curl($url)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i586; rv:31.0) Gecko/20100101 Firefox/31.0');

	if (($met = ini_get('max_execution_time')) > 0) {
		curl_setopt($ch, CURLOPT_TIMEOUT, round($met * 0.9));
	}

	// cURL will follow redirects if open_basedir is not set
	if (false && ini_get('open_basedir') == '') {
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		$return = curl_exec($ch);

		if (curl_errno($ch)) {
			$error = curl_error($ch);
			curl_close($ch);

			throw new RuntimeException($error);
		}

		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($code != 200) {
			throw new RuntimeException('The installation package could not be downloaded');
		}

		return $return;
	}

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

	$new = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	$rch = curl_copy_handle($ch);

	curl_setopt($rch, CURLOPT_HEADER, true);
	curl_setopt($rch, CURLOPT_NOBODY, true);
	curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
	curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);

	$max = 10;

	do {
		curl_setopt($rch, CURLOPT_URL, $new);
		$header = curl_exec($rch);

		if (curl_errno($rch)) {
			$error = curl_error($rch);
			curl_close($rch);

			throw new RuntimeException($error);
		}

		$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);

		if ($code == 301 || $code == 302) {
			$matches = array();
			preg_match('/Location:(.*?)\n/', $header, $matches);
			$new = trim(array_pop($matches));
		} else {
			$code = 0;
		}
	} while ($code && --$max);

	curl_close($rch);

	if ($max > 0) {
		curl_setopt($ch, CURLOPT_URL, $new);
		$return = curl_exec($ch);

		if (curl_errno($ch)) {
			$error = curl_error($ch);
			curl_close($ch);

			throw new RuntimeException($error);
		}
	} else {
		curl_close($ch);

		throw new RuntimeException('Too many redirects');
	}

	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($code != 200) {
		throw new RuntimeException('The installation package could not be downloaded');
	}

	return $return;
}
