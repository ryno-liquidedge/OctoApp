<?php

namespace action\developer\system_user\functions;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xlogin_as implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("dev");
	}
	//--------------------------------------------------------------------------------
	public function run() {
	    message(false);

		// params
		$person = $this->request->getdb("person", true);
		if($person){
			\com\user::login_as($person);
			\com\http::go_home();
		}

        return "stream";
	}
	//--------------------------------------------------------------------------------
}