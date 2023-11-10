<?php

namespace action\developer\system_user\functions;

/**
 * Class xvalidate_change_password
 * @package action\system\setup\system_user\functions
 * @author Ryno Van Zyl
 */


class xvalidate_change_password implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("admins"); }
    //--------------------------------------------------------------------------------
    public function run () {

        $person = $this->request->getobj("person", true);
        $per_password = $this->request->get('per_password', \com\data::TYPE_STRING);
        $per_password_new = $this->request->get('per_password_new', \com\data::TYPE_STRING);
        $per_password_confirm = $this->request->get('per_password_confirm', \com\data::TYPE_STRING);

        $error_arr = [];
        $authenticated = \com\user::authenticate($person->per_username, $per_password);

        if(!$authenticated) $error_arr[] = "Your current password is incorrect.";
        if($per_password_new !== $per_password_confirm) $error_arr[] = "The new password and confirm new password fields does not match.";


        return count($error_arr) > 0 ? \com\ui\helper::return_message(1, implode("<br />", $error_arr)) : \com\ui\helper::return_message(0);

    }
    //--------------------------------------------------------------------------------
}





