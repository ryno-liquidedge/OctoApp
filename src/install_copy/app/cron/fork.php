<?php
/**
 * Fork
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

// setup
include_once("setup.php");

// options
$options = getopt(false, [
	"for_id::",
]);

// if we have a fork id, use it
if (!empty($options["for_id"])) {
	$fork = \com\data::parse_db_identifier($options["for_id"]);
}