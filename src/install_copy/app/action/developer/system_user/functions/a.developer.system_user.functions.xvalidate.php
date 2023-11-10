<?php

namespace action\developer\system_user\functions;

/**
 * Class xvalidate
 * @package action\system\setup\system_user\functions
 * @author Ryno Van Zyl
 */


class xvalidate implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("admins"); }
    //--------------------------------------------------------------------------------
    public function run () {


        $error_arr = [];
        $person = $this->request->getobj("person", true);

        if(!$person->is_unique()) $error_arr[] = "The email address is already registered to another user.";

        if($error_arr) \com\ui\helper::return_message(1, implode("<br>", $error_arr));
        else \com\ui\helper::return_message(0);

        return "stream";

    }
    //--------------------------------------------------------------------------------
}





