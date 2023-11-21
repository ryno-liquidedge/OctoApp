<?php

namespace action\developer;

/**
 * Class vmanage
 * @package action\system\setup
 * @author Ryno Van Zyl
 */

class vmanage implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { 
        return \core::$app->get_token()->check("dev");
    }
    //--------------------------------------------------------------------------------
    public function run () {

        $buffer = \app\ui::make()->html_buffer();

    	$buffer->div_([".container-fluid" => true]);
			$buffer->div_([".row" => true]);
				$buffer->div_([".col-12 col-md-3 col-lg-2" => true]);

					$menu = \app\ui::make()->menu();
					 $fn_add_menu = function($label, $url, $icon) use(&$menu){
					 	$menu->add_link($label, "dev_panel.requestUpdate('$url')", $icon);
					};
					$fn_add_menu("Setup", "?c=developer.general/vedit", "fa-cog");
					$fn_add_menu("Database", "?c=developer.database/vlist", "fa-database");
					$fn_add_menu("User Roles", "?c=developer.acl_role/vlist", "fa-users-cog");
					$fn_add_menu("Person Type", "?c=developer.person_type/vlist", "fa-user-astronaut");
					$fn_add_menu("System Users", "?c=developer.system_user/vtab", "fa-users");
					$fn_add_menu("Crons", "?c=developer.cron_task/vlist", "fa-laptop-code");
					$fn_add_menu("Solid Property Library", "?c=developer.solid_property_library/vtab", "fa-book");
					$fn_add_menu("System Email", "?c=developer.system_email/vsend", "fa-envelope");
					$fn_add_menu("Buffer Builder", "?c=developer.buffer_builder/vedit", "fa-code");
					$fn_add_menu("PHP Info", "?c=developer.php/vinfo", "fa-bars");
					$buffer->add($menu->build());


					$buffer->div_([".mb-2" => true]);
						$buffer->div_();
							$buffer->xlink("javascript:{$this->request->get_panel()}.requestRefresh('?c=developer.functions/xrefresh_htaccess')", "Rebuild .htaccess");
						$buffer->_div();
						$buffer->div_();
							$buffer->xlink("javascript:{$this->request->get_panel()}.requestRefresh('?c=developer.functions/xrebuild_constants')", "Rebuild Constants");
						$buffer->_div();
					$buffer->_div();


				$buffer->_div();
				$buffer->div_([".col" => true]);
					$panel = \app\ui::make()->panel_buffer('?c=developer.general/vedit', ["id" => "dev_panel"]);
					$buffer->add($panel->build());
				$buffer->_div();
			$buffer->_div();
    	$buffer->_div();
    	$buffer->flush();
    }
    //--------------------------------------------------------------------------------
}

