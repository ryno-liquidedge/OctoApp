<?php

namespace LiquidedgeApp\Octoapp\app\app\maint\task;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class clean_sessions extends \com\maint\intf\task {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Clean session files";
		$this->group = "daily";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function run() {

		$session_dir = sys_get_temp_dir();

		// get session files older than 30 days and remove them
		$session_file_arr = \LiquidedgeApp\Octoapp\app\app\os\os::find($session_dir, "/^sess_/i", ["recursive" => true, "max" => 20000, "date_to" => "-1 day"]);
		foreach ($session_file_arr as $session_file_item) {
			// delete the file
			unlink($session_dir."/{$session_file_item}");

			// lets wait for 10ms before we continue
			usleep(10000);
		}
	}
	//--------------------------------------------------------------------------------
}
