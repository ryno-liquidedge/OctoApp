<?php

namespace action\developer\cron_task;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xtoggle implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("dev");
	}
	//--------------------------------------------------------------------------------
	public function run() {

		// params
		$cron_task = $this->request->getdb("cron_task", true);

		// run
		if ($cron_task->is_active()) $cron_task->deactivate();
		else $cron_task->activate();
	}
	//--------------------------------------------------------------------------------
}