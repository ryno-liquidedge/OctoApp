<?php

namespace action\index;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xrole implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("users");
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$role = $this->request->get("role", \com\data::TYPE_STRING, ["get" => true]);

		// change role
		\com\user::change_role($role);
	}
	//--------------------------------------------------------------------------------
}