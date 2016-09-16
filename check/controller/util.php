<?php

/*
 * Contao check
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

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

// Add the gettext function
if (!extension_loaded('gettext')) {
	function _($str) {
		return $str;
	}
}

// Add the posix_getpwuid function
if (!function_exists('posix_getpwuid') && !function_disabled('posix_getpwuid')) {
	function posix_getpwuid($int) {
		return array('name'=>$int);
	}
}
