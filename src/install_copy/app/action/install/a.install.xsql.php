<?php

namespace action\install;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xsql implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		\core::$app->set_section(\acc\core\section\clean::make());
	}
	//--------------------------------------------------------------------------------
	public function auth() {
		return file_exists(\core::$folders->get_root()."/install.php");
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// sql
		$sql = \com\db::getsql_create_database();

		// special log_key case
		switch (\core::$app->get_instance()->get_db_type()) {
			case "sqlsrv":
				$sql .= "\n\nALTER TABLE change_log ALTER COLUMN chl_parent_id INT NULL;";
				break;
				
			case "mysql":
				$sql .= "\n\nALTER TABLE change_log MODIFY chl_parent_id INT NULL;";
				break;
		}

		// done
		\com\http::stream($sql, \core::$app->get_instance()->get_db_name().".sql");
		return "stream";
	}
	//--------------------------------------------------------------------------------
}