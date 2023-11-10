<?php

namespace action\developer\system_user;

/**
 * Class vchange_password
 * @package action\system\setup\system_user
 * @author Ryno Van Zyl
 */


class vchange_password implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("ui_users"); }
    //--------------------------------------------------------------------------------
    public function run () {

        $this->person = $this->request->getdb("person", true);

        // html
        $this->html = \com\ui::make()->html();
		$this->html->form("?c=developer.system_user.functions/xchange_password/{$this->person->per_id}", "?c=developer.system_user.functions/xvalidate_change_password/{$this->person->per_id}", false, ["@autocompete" => "off"]);
		$this->html->header(2, "Change Password");
		$this->html->button("close", "core.browser.close_popup()", ["icon" => "fa-times", "@commodal-btn" => "cancel"]);
		$this->html->submitbutton("Save Changes", false, "parent.requestRefresh", false, false, true, ["icon" => "fa-save", ".btn-success" => true], "core.browser.close_popup();");

		$this->html->header(3, "General Details");
		$append = function($id){ return \com\ui::make()->iconbutton("Show/Hide", "$('#{$id}').attr('type') == 'password' ? $('#{$id}').attr('type', 'text') : $('#{$id}').attr('type', 'password')", "fa-eye"); };
		$this->html->itext("Current Password", "per_password", false, ["required" => true, "label_col" => 4, "mask" => true, "append" => $append("per_password")]);
		$this->html->itext("New Password", "per_password_new", false, ["required" => true, "label_col" => 4, "mask" => true, "append" => $append("per_password_new")]);
		$this->html->itext("Confirm Password", "per_password_confirm", false, ["required" => true, "label_col" => 4, "mask" => true, "append" => $append("per_password_confirm")]);

    }
    //--------------------------------------------------------------------------------
}





