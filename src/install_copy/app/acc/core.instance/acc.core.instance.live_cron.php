<?php

namespace acc\core\instance;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class live_cron extends live {

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct() {
		// init
		$this->id = "";
		$this->name = "Live";
		$this->code = "S";
		$this->url = "https://";
	}
	//--------------------------------------------------------------------------------
}