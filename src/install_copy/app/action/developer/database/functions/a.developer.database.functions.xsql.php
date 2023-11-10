<?php

namespace action\developer\database\functions;

/**
 * @package action\developer\database\functions
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xsql implements \com\router\int\action {

    //--------------------------------------------------------------------------------
    use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    // functions
    //--------------------------------------------------------------------------------
    public function auth() {
        return \core::$app->get_token()->check("dev");
    }
    //--------------------------------------------------------------------------------
    public function run() {

        ini_set('memory_limit', '256M');

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
