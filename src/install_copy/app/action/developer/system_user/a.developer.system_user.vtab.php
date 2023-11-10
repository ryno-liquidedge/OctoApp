<?php

namespace action\developer\system_user;

/**
 * Class vtab
 * @package action\system\setup\system_user
 * @author Ryno Van Zyl
 */

class vtab implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function auth() { return \core::$app->get_token()->check("admins"); }
	//--------------------------------------------------------------------------------
	public function run() {
        
        // request info
        $view = $this->request->get("view", \com\data::TYPE_STRING, ["get" => true]);

        $html = \com\ui::make()->html();
        $html->header(0, "Manage Player Profiles");

        $tab = \com\ui::make()->tab(['id' => 'vtab_profile']);
        if($view) $tab->start_tab_index = $view;
        $tab->add_tab( 'Active Users', "?c=developer.system_user/vlist&context=active");
        $tab->add_tab( 'Inactive Users', "?c=developer.system_user/vlist&context=inactive");

        $html->display( $tab );


        
    }

}
//--------------------------------------------------------------------------------
