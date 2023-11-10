<?php
/**
 * Cron
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

// setup
include_once("setup.php");

// options
$options = getopt(false, [
	"run::",
	"trigger::",
	"force::",
]);

// run cron if specified, or check all triggers
if (!empty($options["run"])) {
	$run = \com\data::parse_db_identifier($options["run"]);
	\com\cron\helper::handle_run($run, ["force" => !empty($options["force"])]);
}
elseif (!empty($options["trigger"])) {
	\com\cron\helper::handle_trigger();
}