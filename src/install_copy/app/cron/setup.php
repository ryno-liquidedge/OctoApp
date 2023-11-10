<?php
/**
 * Setup for core relative to the cron folder
 *
 * @package nova
 * @subpackage core
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

// include core
$_GET["c"] = "index/xconsole";
chdir("../../root");
include_once("../core/core.php");

if (!function_exists('getallheaders')) {
    function getallheaders() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
    }
}

// init core
\core::handle();