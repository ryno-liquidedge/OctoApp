<?php

namespace action\website\index\functions;

/**
 * @package action\website\profile
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class xreset_password implements \com\router\int\action {

	/**
	 * @var \db_person
	 */
    protected $person;

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

    	$request = $this->session->get("change_password_request");
	    $this->person = \core::dbt("person")->splat($request->req_ref_reference);
		if(!$request || !$this->person) return \app\http::go_error_frontend(7);

    	$per_password_new = $this->request->get('per_password_new', \com\data::TYPE_STRING);
    	$per_password_confirm = $this->request->get('per_password_confirm', \com\data::TYPE_STRING);

    	$error_arr = [];
    	if((!$per_password_new || !$per_password_confirm)) $error_arr[] = "The New Password and Confirm Password cannot be empty.";
    	if(($per_password_new != $per_password_confirm)) $error_arr[] = "The New Password and Confirm Password fields must have the same value.";

    	if($error_arr){
    		return \app\http::ajax_response(["alert" => implode("<br>", $error_arr)]);
		}else{
    		if($per_password_new) $this->person->per_password = $per_password_new;
		}

    	$this->person->update();

    	//delete request
    	$request->delete();

    	\com\user::change_user($this->person);

    	return \app\http::ajax_response([
			"redirect" => \app\http::get_seo_url("ui_message", 102),
		]);

    }
    //--------------------------------------------------------------------------------
}

