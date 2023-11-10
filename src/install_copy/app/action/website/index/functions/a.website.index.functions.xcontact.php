<?php

namespace action\website\index\functions;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class xcontact implements \com\router\int\action {

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		\core::$app->set_section(\acc\core\section\application::make());
	}
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

    	$form_id = $this->request->get('form_id', \com\data::TYPE_STRING);

        if(\app\captcha::is_valid()){
            $empty_check = $this->request->get('empty_check', \com\data::TYPE_STRING);
            $message = $this->request->get('message', \com\data::TYPE_TEXT);
            $person = $this->request->getobj("person", true);

            if(!$empty_check && \app\data::is_valid_email($person->per_email))
            	\app\email::email_contact_us_admin($person, $message);
        }

        return \app\http::ajax_response([
            "title" => "Request Sent",
            "alert" => "Thank you for your request. We will be in contact with you soon.",
            "js" => "
                core.form.clear_form_fields('#{$form_id}');
            ",
        ]);

    }
    //--------------------------------------------------------------------------------
}

