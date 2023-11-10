<?php

namespace action\developer\php;

/**
 * Class vlist
 * @package action\system\setup\property_config
 * @author Ryno Van Zyl
 */

class vinfo implements \com\router\int\action {

	private $sms = false;

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() { 
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {
		echo phpinfo();
    }
	//--------------------------------------------------------------------------------
}
