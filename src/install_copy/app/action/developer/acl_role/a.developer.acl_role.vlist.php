<?php

namespace action\developer\acl_role;

/**
 * Class vlist
 * @package action\system\setup\acl_role
 * @author Ryno Van Zyl
 */

class vlist implements \com\router\int\action {

	private $sms = false;

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() { 
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

		$list = \com\ui::make()->table();

		$list->key = "acl_id";
		$list->sql_select = "*";
		$list->sql_from = "acl_role";
		$list->sql_orderby = "acl_level ASC";
		$list->quickfind_field = \com\db::getsql_concat(["acl_name", "acl_code"]);
		$list->nav_append_end = function($list, $toolbar){
            $toolbar->add_button("Add New Role", "{$this->request->get_panel()}.popup('?c=developer.acl_role/vadd', {width:'40%'});");
            $toolbar->add_button("Install From DB", "{$this->request->get_panel()}.requestRefresh('?c=developer.acl_role.functions/xinstall_from_db');");
            $toolbar->add_button("Install From Class", "{$this->request->get_panel()}.requestRefresh('?c=developer.acl_role.functions/xinstall_from_class');");
            $toolbar->add_button("Install Constants", "{$this->request->get_panel()}.requestRefresh('?c=developer.acl_role.functions/xinstall_constants');");
        };

		// fields
		$list->add_field("Name", "acl_name");
		$list->add_field("Code", "acl_code");
		$list->add_field("Is Locked", "acl_is_locked", ["format" => DB_BOOL]);
		$list->add_field("Level", "acl_level");

		$list->add_action("Edit", "{$this->request->get_panel()}.popup('?c=developer.acl_role/vedit/%acl_id%', {width:'40%'});", "fa-pencil-alt");

		$html = \com\ui::make()->html();
		$html->header(2, "System Roles");
		$html->display($list);
	
    }
	//--------------------------------------------------------------------------------
	public function custom_nav() {
	    $toolbar = \com\ui::make()->toolbar();
	    $toolbar->add_button("Add New Role", "{$this->request->get_panel()}.popup('?c=developer.acl_role/vadd', {width:'40%'});");
	    $toolbar->add_divider();
	    $toolbar->add_button("Install From DB", "{$this->request->get_panel()}.requestRefresh('?c=developer.acl_role.functions/xinstall_from_db');");
	    $toolbar->add_divider();
	    $toolbar->add_button("Install From Class", "{$this->request->get_panel()}.requestRefresh('?c=developer.acl_role.functions/xinstall_from_class');");
	    return $toolbar->build();
	}
	//--------------------------------------------------------------------------------
}
