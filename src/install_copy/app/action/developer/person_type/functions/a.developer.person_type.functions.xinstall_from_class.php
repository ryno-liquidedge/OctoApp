<?php

namespace action\developer\person_type\functions;

/**
 * Class xinstall_from_class
 * @package action\system\setup\person_type\functions
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
		\app\install\person_type\make\install::make_db_enties();
    }
    //--------------------------------------------------------------------------------
}

