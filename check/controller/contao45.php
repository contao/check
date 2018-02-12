<?php

/*
 * Contao check
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

require_once __DIR__ . '/contao4.php';

/**
 * Check the Contao 4.5 requirements
 *
 * @author Fritz Michael Gschwantner <https://github.com/fritzmg>
 */
class Contao45 extends Contao4
{
    const PHP_VERSION = '7.1.0';
    const ICU_VERSION = '4.6';

    /**
     * @var string
     */
    protected $icuVersion = null;

    /**
     * Execute the command
     */
    public function run()
    {
        include __DIR__ . '/../views/contao45.phtml';
    }

    /**
     * Returns the detected ICU version
     *
     * @return string
     */
    public function getIcuVersion()
    {
        return $this->icuVersion;
    }

    /**
     * Executes all compatibility checks.
     *
     * @return boolean True if Contao 4.5 can be run
     */
    public function checkCompatibility()
    {
        parent::checkCompatibility();

        if (!$this->compatible) {
            return false;
        }

        if (!$this->hasIcu()) {
            return false;
        }

        return true;
    }

     /**
     * Checks the minimum version of the ICU library
     *
     * @return boolean True if the version of the ICU library is sufficiently high
     */
    public function hasIcu()
    {
        if (extension_loaded('intl')) {

            if (defined('INTL_ICU_VERSION')) {
                $this->icuVersion = INTL_ICU_VERSION;
            } else {
                $reflector = new \ReflectionExtension('intl');
                ob_start();
                $reflector->info();
                $output = strip_tags(ob_get_clean());
                preg_match('/^ICU version +(?:=> )?(.*)$/m', $output, $matches);
                $this->icuVersion = $matches[1];
            }

            if (version_compare($this->icuVersion, static::ICU_VERSION, '>=')) {
                return true;
            }
        }

        $this->compatible = false;

        return false;
    }
}
