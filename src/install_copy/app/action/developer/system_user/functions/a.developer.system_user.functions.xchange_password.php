<?php

namespace action\developer\system_user\functions;

/**
 * Class xchange_password
 * @package action\system\setup\system_user\functions
 * @author Ryno Van Zyl
 */


class xchange_password implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("admins"); }
    //--------------------------------------------------------------------------------
    public function run () {

        $this->per_password_new = $this->request->get('per_password_new', \com\data::TYPE_STRING);

        $person = $this->request->getdb("person", true);
        $person->per_password = $this->per_password_new;
        $person->update();

    }
    //--------------------------------------------------------------------------------
}





