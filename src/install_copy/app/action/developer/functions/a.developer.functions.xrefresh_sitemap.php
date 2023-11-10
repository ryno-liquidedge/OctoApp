<?php

namespace action\developer\functions;

/**
 * Class xadd
 * @package action\system\setup\person_type\functions
 * @author Ryno Van Zyl
 */


class xrefresh_sitemap implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

    	$sitemap_coder = \app\inc\google\sitemap_coder::make();
		$sitemap_coder->build();
    }
    //--------------------------------------------------------------------------------
}

