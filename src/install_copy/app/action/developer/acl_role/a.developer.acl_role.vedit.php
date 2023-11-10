<?php

namespace action\developer\acl_role;

/**
 * Class vedit
 * @package action\system\setup\acl_role
 * @author Ryno Van Zyl
 */

class vedit implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

        $acl_role = $this->request->getdb("acl_role", true);
		
        // html
        $html = \com\ui::make()->html();
        $html->header(2, "Edit Role");
        $html->form("?c=developer.acl_role.functions/xedit/$acl_role->id");
        $html->button("cancel", "core.browser.close_popup()", [".btn-cancel" => true, "icon" => "fa-times"]);
        if(!(bool)$acl_role->acl_is_locked)$html->submitbutton("Save Changes", false, "parent.requestRefresh", false, false, false, ["icon" => "fa-edit", ".btn-success" => true], "core.browser.close_popup();");

        // header
        $html->header(3, "General Details");
        $html->dbinput($acl_role, "acl_name", ["required" => true, "@disabled" => (bool)$acl_role->acl_is_locked]);
        $html->dbinput($acl_role, "acl_code", ["required" => true, "@disabled" => (bool)$acl_role->acl_is_locked]);
        $html->dbinput($acl_role, "acl_level", ["required" => true, "@disabled" => (bool)$acl_role->acl_is_locked]);
    }
    //--------------------------------------------------------------------------------
}

