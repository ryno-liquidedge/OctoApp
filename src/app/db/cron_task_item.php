<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class cron_task_item extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "cron_task_item";
	public $key = "cri_id";
	public $display = "cri_ref_cron_task";
	public $audit = false;
	public $trace = false;

	public $display_name = "cron task item";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"cri_id"				=> array("id"					, "null", DB_KEY),
		"cri_ref_cron_task"		=> array("relatedcron task"		, "null", DB_REFERENCE, "cron_task"),
		"cri_date_start"		=> array("date started"			, "null", DB_DATETIME),
		"cri_date_end"			=> array("date ended"			, "null", DB_DATETIME),
		"cri_duration"			=> array("duration"				, 0		, DB_SECONDS),
		"cri_status"			=> array("duration"				, 0		, DB_ENUM),
		"cri_pid"				=> array("pid"					, ""	, DB_STRING),
	);
	//--------------------------------------------------------------------------------
	public $cri_status = [
		0 => "-- Not Selected --",
		1 => "Running",
		2 => "Completed",
		3 => "Cancelled",
		4 => "Error",
		5 => "Terminated",
	];
	//--------------------------------------------------------------------------------
	// events
	//--------------------------------------------------------------------------------
	public function on_auth(&$cron_task_item, $user, $role) {
		return in_array($role, ["DEV", "ADMIN"]);
	}
    //--------------------------------------------------------------------------------
	public function on_auth_use(&$cron_task_item, $user, $role) {
		return $this->auth_for($cron_task_item, $user, $role);
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function complete($cron_task_item) {
		// params
		$cron_task_item = $this->splat($cron_task_item);

		// update appropriate fields for completion
		$cron_task_item->cri_status = 2; // completed
		$cron_task_item->cri_date_end = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime("now");
		$cron_task_item->cri_duration = \LiquidedgeApp\Octoapp\app\app\date\date::second_diff($cron_task_item->cri_date_start, $cron_task_item->cri_date_end);
		$cron_task_item->save();
	}
	//--------------------------------------------------------------------------------
	public function cancel($cron_task_item) {
		// params
		$cron_task_item = $this->splat($cron_task_item);

		// update appropriate fields for cancellation
		$cron_task_item->cri_status = 3; // cancelled
		$cron_task_item->cri_date_end = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime("now");
		$cron_task_item->cri_duration = \LiquidedgeApp\Octoapp\app\app\date\date::second_diff($cron_task_item->cri_date_start, $cron_task_item->cri_date_end);
		$cron_task_item->save();
	}
	//--------------------------------------------------------------------------------
	public function terminate($cron_task_item) {
		// params
		$cron_task_item = $this->splat($cron_task_item);

		// update appropriate fields for termination
		$cron_task_item->cri_status = 5; // terminated
		$cron_task_item->cri_date_end = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime("now");
		$cron_task_item->cri_duration = \LiquidedgeApp\Octoapp\app\app\date\date::second_diff($cron_task_item->cri_date_start, $cron_task_item->cri_date_end);
		$cron_task_item->save();
	}
	//--------------------------------------------------------------------------------
	public function error($cron_task_item) {
		// params
		$cron_task_item = $this->splat($cron_task_item);

		// update appropriate fields for an error
		$cron_task_item->cri_status = 4; // error
		$cron_task_item->cri_date_end = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime("now");
		$cron_task_item->cri_duration = \LiquidedgeApp\Octoapp\app\app\date\date::second_diff($cron_task_item->cri_date_start, $cron_task_item->cri_date_end);
		$cron_task_item->save();
	}
	//--------------------------------------------------------------------------------
	public function is_process_running($cron_task_item) {
		// params
		$cron_task_item = $this->splat($cron_task_item);

		// done
		return ($cron_task_item->cri_pid && \com\os::is_task_running("php.exe", $cron_task_item->cri_pid));
//		return ($cron_task_item->cri_pid && \com\os::is_task_running("php-win.exe", $cron_task_item->cri_pid));
	}
	//--------------------------------------------------------------------------------
	public function kill($cron_task_item) {
		// params
		$cron_task_item = $this->splat($cron_task_item);

		// kill process id
		if ($cron_task_item->is_process_running()) {
			$result = \com\os::kill_task($cron_task_item->cri_pid);
			if ($cron_task_item->is_process_running()) {
				return \com\error::create("Failed to kill the cron with PID {$cron_task_item->cri_pid}");
			}
		}

		// terminate
		$cron_task_item->terminate();
	}
	//--------------------------------------------------------------------------------
}