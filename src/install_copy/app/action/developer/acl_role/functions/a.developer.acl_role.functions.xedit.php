<?php

namespace action\developer\acl_role\functions;

/**
 * Class xedit
 * @package action\system\setup\acl_role\functions
 * @author Ryno Van Zyl
 */

class xedit implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

		$acl_role = $this->request->getobj("acl_role", true);
		$acl_role->update();

		\app\install\acl_role\make\install::install_from_db();
		\app\install\acl_role\make\install::build_constants();

    }
    //--------------------------------------------------------------------------------
}

