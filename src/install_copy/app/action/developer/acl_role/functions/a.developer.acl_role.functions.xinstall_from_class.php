<?php

namespace action\developer\acl_role\functions;

/**
 * Class xinstall_from_class
 * @package action\system\setup\acl_role\functions
 * @author Ryno Van Zyl
 */

class xinstall_from_class implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

        \app\install\acl_role\make\install::make_db_enties();
    }
    //--------------------------------------------------------------------------------
}

