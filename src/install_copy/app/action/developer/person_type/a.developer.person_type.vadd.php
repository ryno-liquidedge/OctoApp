<?php

namespace action\developer\person_type;

/**
 * Class vadd
 * @package action\system\setup\person_type
 * @author Ryno Van Zyl
 */

class vadd implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

        $person_type = \core::dbt("person_type")->get_fromdefault();
		$person_type->acl_level = false;

        // html
        $html = \com\ui::make()->html();
        $html->header(2, "Add New Person Type");
        $html->form("?c=developer.person_type.functions/xadd");
        $html->button("cancel", "core.browser.close_popup()", [".btn-cancel" => true, "icon" => "fa-times"]);
        $html->submitbutton("Save Changes", false, "parent.requestRefresh", false, false, false, ["icon" => "fa-save", ".btn-success" => true], "core.browser.close_popup();");

        // header
        $html->header(3, "General Details");
        $html->dbinput($person_type, "pty_name", ["required" => true]);
        $html->dbinput($person_type, "pty_code", ["required" => true]);
        $html->dbinput($person_type, "pty_class");
        $html->dbinput($person_type, "pty_is_individual");
    }
    //--------------------------------------------------------------------------------
}

