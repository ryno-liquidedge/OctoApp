<?php

namespace action\developer\person_type;

/**
 * Class vlist
 * @package action\system\setup\person_type
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

		$list->key = "pty_id";
		$list->sql_select = "*";
		$list->sql_from = "person_type";
		$list->quickfind_field = \com\db::getsql_concat(["pty_name"]);
		$list->nav_append_end = $this->custom_nav();

		// fields
		$list->add_field("Name", "pty_name");
		$list->add_field("Code", "pty_code");
		$list->add_field("Is Individual", "pty_is_individual", ["format" => DB_BOOL]);
		$list->add_field("Class", "pty_class");

		$list->add_action("Edit", "{$this->request->get_panel()}.popup('?c=developer.person_type/vedit/%pty_id%', {width:'40%'});", "fa-pencil-alt");

		$html = \com\ui::make()->html();
		$html->header(2, "Person Types");
		$html->display($list);

    }
	//--------------------------------------------------------------------------------
	public function custom_nav() {
	    $toolbar = \com\ui::make()->toolbar();
	    $toolbar->add_button("Add New Person type", "{$this->request->get_panel()}.popup('?c=developer.person_type/vadd', {width:'40%'});");
	    $toolbar->add_divider();
	    $toolbar->add_button("Install From DB", "{$this->request->get_panel()}.requestRefresh('?c=developer.person_type.functions/xinstall_from_db');");
	    $toolbar->add_divider();
	    $toolbar->add_button("Install From Class", "{$this->request->get_panel()}.requestRefresh('?c=developer.person_type.functions/xinstall_from_class');");
	    return $toolbar->build();
	}
	//--------------------------------------------------------------------------------
}
