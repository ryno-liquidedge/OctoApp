<?php

namespace action\developer\cron_task;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vget_all_cron_command implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("dev");
	}
	//--------------------------------------------------------------------------------
	public function run() {


	    $html = \com\ui::make()->html();
		$html->header(2, "Cron Commands");

	    $cron_task_arr = \core::dbt("cron_task")->get_fromdb("cro_is_active = 1", ["multiple" => true]);

	    $commands = [];
	    foreach ($cron_task_arr as $cron_task) $commands[] = $this->get_command($cron_task);

	    $html->itextarea(false, "commands", implode("\n", $commands));


	}
	//--------------------------------------------------------------------------------
    public function get_command($cron_task) {

	    $db_host = \core::$app->get_instance()->get_db_host();
	    $server = $db_host == "localhost" ? "__username__" : $this->get_username();

        return "*/5 * * * * /usr/bin/php-wrapper /usr/home/{$server}/public_html/app/cron/cron_{$cron_task->cro_class}.php > /dev/null 2>&1";
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