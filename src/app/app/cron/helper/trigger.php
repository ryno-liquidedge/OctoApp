<?php

namespace LiquidedgeApp\Octoapp\app\app\cron\helper;

/**
 * @package app\cron\cron
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class trigger extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	//--------------------------------------------------------------------------------
	public function run($options = []) {

		// find all registered tasks
		$cron_task_arr = \core::dbt("cron_task")->get_fromdb("cro_is_active = 1", ["multiple" => true]);

		foreach ($cron_task_arr as $cron_task) {
			$this->handle_run($cron_task->cro_class);
		}
	}
	//--------------------------------------------------------------------------------
	public function handle_run($task, $options = []) {
		// options
		$options = array_merge([
			"force" => false,
		], $options);

		// params
		$task = \com\cron\helper::make($task);

		// check if we have a task
		if (!$task) return false;

		// check for external errors and resolve as needed
		$task->failsafe();

		// check statuses
		if (!$options["force"]) {

			if (!$task->is_environment()) return false;
			if (!$task->is_enabled()) return false;
			if (!$task->is_role()) return false;

            // check if we should run this task
            if (!$task->is_due()) return false;

            if (!$task->is_active()) return false;
		}


		// check if we are busy running this task
		if ($task->is_running()) return false;

		// run
		$task->run();
	}
	//--------------------------------------------------------------------------------
}
