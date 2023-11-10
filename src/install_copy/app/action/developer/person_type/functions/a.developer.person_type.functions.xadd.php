<?php

namespace action\developer\person_type\functions;

/**
 * Class xadd
 * @package action\system\setup\person_type\functions
 * @author Ryno Van Zyl
 */


class xadd implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {
		$person_type = $this->request->getobj("person_type", true);
		$person_type->insert();

		\app\install\person_type\make\install::install_from_db();
    }
    //--------------------------------------------------------------------------------
}

