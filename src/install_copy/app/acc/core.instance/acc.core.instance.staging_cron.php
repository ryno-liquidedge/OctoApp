<?php

namespace acc\core\instance;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class staging_cron extends staging {

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct() {
		// init
		$this->id = "";
		$this->name = "Staging";
		$this->code = "S";
		$this->url = "https://";
	}
	//--------------------------------------------------------------------------------
}