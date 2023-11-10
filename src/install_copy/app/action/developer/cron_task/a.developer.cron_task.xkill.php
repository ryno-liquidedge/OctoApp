<?php

namespace action\developer\cron_task;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xkill implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("dev");
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$cron_task_item = $this->request->getdb("cron_task_item", true);

		// run
		$cron_task_item->kill();
	}
	//--------------------------------------------------------------------------------
}