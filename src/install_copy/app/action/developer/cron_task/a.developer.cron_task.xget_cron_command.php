<?php

namespace action\developer\cron_task;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xget_cron_command implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("dev");
	}
	//--------------------------------------------------------------------------------
	public function run() {

	    $db_host = \core::$app->get_instance()->get_db_host();
	    $server = $db_host == "localhost" ? "__username__" : $this->get_username();
	    $cron_task = $this->request->getdb("cron_task", true);

	    \com\http::json("*/5 * * * * /usr/bin/php-wrapper /usr/home/{$server}/public_html/app/cron/cron_{$cron_task->cro_class}.php > /dev/null 2>&1");

		return "stream";
	}
	//--------------------------------------------------------------------------------
    public function get_username() {
        $id = \core::$app->get_instance()->get_id();
        $parts = explode("/", $id);
        $users_key = array_search("users", $parts);
        return $parts[$users_key+1];
    }
	//--------------------------------------------------------------------------------
}