<?php
/**
 * Index page handler
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

// include core
include_once("../core/core.php");

// handle url
$success = \core::handle();
if (!$success) \com\http::go_home();