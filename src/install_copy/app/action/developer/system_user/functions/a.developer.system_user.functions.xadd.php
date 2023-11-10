<?php

namespace action\developer\system_user\functions;

/**
 * Class xadd
 * @package action\system\setup\system_user\functions
 * @author Ryno Van Zyl
 */


class xadd implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("admins"); }
    //--------------------------------------------------------------------------------
    public function run () {

        message(false);

        $email_reset_password = $this->request->get('email_reset_password', \com\data::TYPE_STRING);

        $acl_code_arr = $this->request->get('acl_code_arr', \com\data::TYPE_STRING, ["default" => []]);

        $person = $this->request->getobj("person", true);
        $person->per_username = $person->per_email;
        $person->insert();

        $acl_code_arr = \com\arr::splat($acl_code_arr);
        foreach ($acl_code_arr as $acl_code) $person->add_role($acl_code);

        if($email_reset_password){
            // send forgot password email
            $code = \app\user::email_change_password($person->per_username, ["return_error_code" => true]);
        }

        message(true, "New User Saved");

    }
    //--------------------------------------------------------------------------------
}





