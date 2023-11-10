<?php

namespace action\index;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xforgotpassword implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$per_email = $this->request->get("per_email", \com\data::TYPE_STRING);


		if(!$per_email){
			return \app\http::ajax_response(["alert" => "Email field is required"]);
		}


		$person = \core::dbt("person")->find([".per_email" => $per_email]);
		if(!$person) return \app\http::ajax_response([
		    "alert" => "Email address not registered"
        ]);

		//send account verification email
        \app\email::make()->send_reset_password_email($person);

		return \app\http::ajax_response([
			"no_overlay" => true,
			"redirect" => \app\http::get_seo_url("ui_message", 3)
		]);
	}
	//--------------------------------------------------------------------------------
}