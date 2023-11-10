<?php

namespace action\developer\acl_role;

/**
 * Class vadd
 * @package action\system\setup\acl_role
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

        $acl_role = \core::$db->acl_role->get_fromdefault();
		$acl_role->acl_level = false;

        // html
        $html = \com\ui::make()->html();
        $html->header(2, "Add New Role");
        $html->form("?c=developer.acl_role.functions/xadd");
        $html->button("cancel", "core.browser.close_popup()", [".btn-cancel" => true, "icon" => "fa-times"]);
        $html->submitbutton("Save Changes", false, "parent.requestRefresh", false, false, false, ["icon" => "fa-save", ".btn-success" => true], "core.browser.close_popup();");

        // header
        $html->header(3, "General Details");
        $html->dbinput($acl_role, "acl_name", ["required" => true]);
        $html->dbinput($acl_role, "acl_code", ["required" => true]);
        $html->dbinput($acl_role, "acl_level", ["required" => true]);
        $html->dbinput($acl_role, "acl_is_locked");
    }
    //--------------------------------------------------------------------------------
}

