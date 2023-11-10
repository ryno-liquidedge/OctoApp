<?php

namespace action\index;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xloginas_revert implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("ui_users");
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// revert
		\com\user::revert_login_as();
		\com\http::go_home();
	}
	//--------------------------------------------------------------------------------
}