<?php

namespace action\developer\general\functions;

/**
 * Class xedit
 * @package action\system\setup\acl_role\functions
 * @author Ryno Van Zyl
 */

class xreset_bs_colors implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

        message(false);

        $reset_color = function($key){
        	$solid = \app\solid::get_setting_instance($key);
        	$solid->save_value($solid->get_default());
		};

        $reset_color(SETTING_BS_PRIMARY);
		$reset_color(SETTING_BS_SECONDARY);
		$reset_color(SETTING_BS_SUCCESS);
		$reset_color(SETTING_BS_INFO);
		$reset_color(SETTING_BS_WARNING);
		$reset_color(SETTING_BS_DANGER);
		$reset_color(SETTING_BS_LIGHT);
		$reset_color(SETTING_BS_DARK);

		//attempt so override bootstrap colors
		\app\helper\scss\variables_compiler::make()->run();


        message(true, "Changes Saved");
    }
    //--------------------------------------------------------------------------------
}

