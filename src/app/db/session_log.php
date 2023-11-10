<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class session_log extends \com\session\db\session_log {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------

 	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function on_save(&$session_log) {
		// limits
		if (strlen($session_log->sel_url) > 255) {
			$session_log->sel_url = substr($session_log->sel_url, 0, 256);
		}

		if($session_log->is_empty("sel_controller")) return false;
	}
 	//--------------------------------------------------------------------------------
}