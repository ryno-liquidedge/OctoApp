<?php

namespace action\index;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class verror_debug implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {
		if (\core::$app->get_instance()->get_environment() == "LIVE") \com\http::go_error(10);

		// html
		\com\error::view();
		return "clean";
	}
	//--------------------------------------------------------------------------------
}