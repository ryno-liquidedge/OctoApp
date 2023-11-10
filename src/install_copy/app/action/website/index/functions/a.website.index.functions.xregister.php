<?php

namespace action\website\index\functions;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class xregister implements \com\router\int\action {

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

        $this->person = $this->request->getobj("person", true);
	    $this->person->per_terms_accepted = (bool) $this->request->get('per_terms_accepted', \com\data::TYPE_STRING);
	    $per_password_register = $this->request->get('per_password_register', \com\data::TYPE_STRING);
	    $per_password_register_confirm = $this->request->get('per_password_register_confirm', \com\data::TYPE_STRING);

		$error_arr = [];
	    if(!$this->person->per_terms_accepted) $error_arr[] = "Please check the terms and conditions before you continue?";
	    if($this->person->is_empty("per_firstname")) $error_arr[] = "The name field is a required field";
	    if($this->person->is_empty("per_lastname")) $error_arr[] = "The surname field is a required field";
	    if($this->person->is_empty("per_email")) $error_arr[] = "The email field is a required field";
	    if(!$this->person->is_unique()) $error_arr[] = "The email you have used is already registered. Please use forgot password to reset your password.";
	    if($per_password_register !== $per_password_register_confirm) $error_arr[] = "The Password and Confirm Password fields do not match.";

	    if($error_arr) return \app\http::ajax_response(["message" => implode("<br>", $error_arr)]);

        //check to see if the person exists but has a pending verification email request
        $person = \core::dbt("person")->splat($this->person->per_email);

        if($person && $person->per_is_active == 2) {
            if($person->per_is_active == 2){
                return \app\http::ajax_response([
                    "title" => "Account Pending Verification",
                    "message" => "Seems like you have registered before, but your account is pending verification. We will resend you the verification email shortly.",
                    "ok_callback" => \app\js::ajax("?c=website.index.functions/xresend_verification_email&id=".md5($person->per_id), [
                        "*beforeSend" => "function(){
                            core.overlay.show();
                        }",
                        "*success" => "function(){
                            document.location='".\app\http::get_seo_url("ui_message", 100)."';
                        }"
                    ]),
                ]);
            }else{
                return \app\http::ajax_response(["message" => "The email address you have used already exists."]);
            }
        }else{

		    //save new person entry
	        $this->person->per_username = $this->person->per_email;
	        $this->person->per_password = $per_password_register;
	        $this->person->per_ref_person_type = \core::dbt("person_type")->splat("individual")->id;
	        $this->person->per_is_active = 2;
	        $this->person->per_date_created = \com\date::strtodatetime();
	        $this->person->save();

	        if(!$this->person->has_role(ACL_CODE_CLIENT)) $this->person->add_role(ACL_CODE_CLIENT);

        }

        //send account verification email
        \app\email::make()->send_account_verification_email($this->person);

        return \app\http::ajax_response([
            "redirect" => \app\http::get_seo_url("ui_message", 100),
        ]);

    }
    //--------------------------------------------------------------------------------
}

