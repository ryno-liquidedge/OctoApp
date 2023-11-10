<?php

namespace action\website\index\functions;

/**
 * @package action\website\index\functions
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class xverify_account implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$req_code = $this->request->get("req_code", \com\data::TYPE_STRING, ["get" => true]);

		// request
		$request = \core::dbt("request")->get_fromdb("req_code = ".dbvalue($req_code));
		if (!$request) \app\http::go_error_frontend(7);

		// person
		$person = \core::dbt('person')->get_fromdb($request->req_ref_reference);
		if (!$person) \app\http::go_error_frontend(7);


		//update account
		if($person->per_is_active != 1){
			$person->per_is_active = 1;
			$person->update();
		}

		switch ($request->req_type){
            case 1: $this->session->change_password_request = $request; break;
            case 2:
                $this->session->change_password_request = $request;
                //log user in
                \app\user::change_user($person);
                break;
            default:
                //delete request
                $request->delete();
                break;
        }


		if($request->req_data) return \app\http::redirect($request->req_data);


		return \app\http::redirect(\app\http::get_seo_url("ui_message", 100));
	}
	//--------------------------------------------------------------------------------
}