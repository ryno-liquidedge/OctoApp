<?php

namespace action\developer\system_user;

/**
 * Class vadd
 * @package action\system\setup\system_user
 * @author Ryno Van Zyl
 */


class vadd implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("ui_users"); }
    //--------------------------------------------------------------------------------
    public function run () {
        $email_reset_password = $this->request->get('email_reset_password', \com\data::TYPE_INT, ["default" => 0]);
        $person = \core::dbt("person")->get_fromdefault();
        $person->merge_withrequest();

        // html
        $html = \com\ui::make()->html();
		$html->form("?c=developer.system_user.functions/xadd", "?c=developer.system_user.functions/xvalidate", false, ["@autocompete" => "off"]);
		$html->header(2, "Add new User");
		$html->button("close", "core.browser.close_popup()", ["icon" => "fa-times", "@commodal-btn" => "cancel"]);
		$html->submitbutton("Save Changes", false, "parent.requestRefresh", false, false, true, ["icon" => "fa-save", ".btn-success" => true], "core.browser.close_popup();");

		$html->header(3, "General Details");
		$html->dbinput($person, "per_firstname", ["required" => true]);
		$html->dbinput($person, "per_lastname", ["required" => true]);
		$html->dbinput($person, "per_tradingname");

		$html->header(3, "Account Details");
		$html->dbinput($person, "per_email", ["required" => true]);

		$html->iradio("Email Reset Password Link", "email_reset_password", [0 => "No", 1 => "Yes"], $email_reset_password, ["inline" => true, "!click" => "{$this->request->get_panel()}.refresh(null, {form:'#{$html->form->id_form}'})"]);

		if(!$email_reset_password){
            $html->itext("Password", "per_password", false, ["@type" => "password", "required" => true, "@autocomplete" => "false", "mask" => true]);
            $html->itext("Confirm Password", "per_password_confirm", false, ["@type" => "password", "required" => true, "@autocomplete" => "false", "mask" => true]);
            $html->form->add_validation_notequal("per_password", "Password", "per_password_confirm", "Confirm Password");
        }

		$sql = \com\db\sql\select::make();
		$sql->select("acl_code");
		$sql->from("acl_role");

        $html->icheckbox("Roles", "acl_code_arr", \core::db()->selectlist($sql->build(), "acl_code", "acl_code"), false);
		$html->ihidden("per_is_active", 1);

    }
    //--------------------------------------------------------------------------------
}





