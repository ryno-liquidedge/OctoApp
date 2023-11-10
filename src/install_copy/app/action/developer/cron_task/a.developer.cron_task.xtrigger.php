<?php

namespace action\developer\cron_task;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xtrigger implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("dev");
	}
	//--------------------------------------------------------------------------------
	public function run() {
		\com\cron\helper::handle_trigger();
	}
	//--------------------------------------------------------------------------------
}