<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// ------------------------------------------------------------------------

/**
 * Dotenv Hook
 * 
 * Load the environment variables from the .env file.
 * 
 * @author	Romain Lacits
 * @return void
 */
$hook['pre_system'] = function () {
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    $dotenv->load(__DIR__. '/../../.env.' . ENVIRONMENT);
};

// ------------------------------------------------------------------------
