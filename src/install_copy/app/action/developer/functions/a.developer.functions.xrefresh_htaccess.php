<?php

namespace action\developer\functions;

/**
 * Class xadd
 * @package action\system\setup\person_type\functions
 * @author Ryno Van Zyl
 */


class xrefresh_htaccess implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

    	\app\seo\coder::make()->build();
    }
    //--------------------------------------------------------------------------------
}

