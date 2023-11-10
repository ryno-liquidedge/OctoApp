<?php

namespace action\index;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xlogin implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$username = $this->request->get("username", \com\data::TYPE_STRING);
		$password = $this->request->get("password", \com\data::TYPE_STRING);

		// login
		$code = \app\user::login_system($username, $password, true);

		if($code == 0){
			return \app\http::ajax_response(["redirect" => "?c=index/xhome"]);
		}

		$message_arr = \app\http::$message_arr;
		$message_data = isset($message_arr[$code]) ? $message_arr[$code] : $message_arr[500];

		return \app\http::ajax_response(["title" => $message_data["title"], "alert" => $message_data["message"]]);
	}
	//--------------------------------------------------------------------------------
}