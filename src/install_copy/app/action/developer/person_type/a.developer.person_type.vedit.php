<?php

namespace action\developer\person_type;

/**
 * Class vedit
 * @package action\system\setup\person_type
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

        $person_type = $this->request->getdb("person_type", true);
		
        // html
        $html = \com\ui::make()->html();
        $html->header(2, "Edit Person Type");
        $html->form("?c=developer.person_type.functions/xedit/$person_type->id");
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

