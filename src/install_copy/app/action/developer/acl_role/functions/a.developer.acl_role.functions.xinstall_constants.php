<?php

namespace action\developer\acl_role\functions;

/**
 * Class xinstall_from_db
 * @package action\system\setup\acl_role\functions
 * @author Ryno Van Zyl
 */

class xinstall_constants implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {
        \app\install\acl_role\make\install::build_constants();
    }
    //--------------------------------------------------------------------------------
}

