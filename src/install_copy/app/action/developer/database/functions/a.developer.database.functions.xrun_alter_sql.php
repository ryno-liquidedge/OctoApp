<?php

namespace action\developer\database\functions;

/**
 * @package action\developer\database\functions
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xrun_alter_sql implements \com\router\int\action {

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
		
		$name = $this->request->get("name", \com\data::TYPE_STRING, ["get" => true]);

		\core::db()->query(\com\db::getsql_alter_table($name));

		message("SQL Successfully Executed");
    }
    //--------------------------------------------------------------------------------
}
