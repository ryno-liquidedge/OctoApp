<?php

namespace action\developer\database\functions;

/**
 * @package action\developer\database\functions
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xgenerate_config implements \com\router\int\action {

    //--------------------------------------------------------------------------------
    use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    // functions
	//--------------------------------------------------------------------------------
    protected function __construct($options = []) {
		\core::$app->set_section(\acc\core\section\application::make());
	}
    //--------------------------------------------------------------------------------
    public function auth() {
        return \core::$app->get_token()->check("dev");
    }
    //--------------------------------------------------------------------------------
    public function run() {
		
		$builder = \com\coder\builder\config::make();
        $builder->build();
		
		message("Config File Successfully Generated");
    }
    //--------------------------------------------------------------------------------
}
