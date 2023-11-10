<?php

namespace action\developer\functions;

/**
 * Class xadd
 * @package action\system\setup\person_type\functions
 * @author Ryno Van Zyl
 */


class xrebuild_constants implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

    	\app\solid::install();

        \app\install\acl_role\make\install::install_from_db();
        \app\install\acl_role\make\install::install_from_solid();
        \app\install\acl_role\make\install::build_constants();

        \app\install\person_type\make\install::install_from_db();
        \app\install\person_type\make\install::install_from_solid();
        \app\install\person_type\make\install::build_constants();
    }
    //--------------------------------------------------------------------------------
}

