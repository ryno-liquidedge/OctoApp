<?php

namespace action\developer\system_user;

/**
 * Class vedit
 * @package action\system\setup\system_user
 * @author Ryno Van Zyl
 */


class vedit implements \com\router\int\action {
    //--------------------------------------------------------------------------------
    use \com\router\tra\action;

    //--------------------------------------------------------------------------------
    public function auth() {
        return \core::$app->get_token()->check("ui_users");
    }

    //--------------------------------------------------------------------------------
    public function run() {
        $person = $this->request->getdb("person", true);
        $person->merge_withrequest();

        // html
        $html = \com\ui::make()->html();
        $html->header(2, "Edit User");
        $html->form("?c=developer.system_user.functions/xedit/$person->id", "?c=developer.system_user.functions/xvalidate/$person->id");
        $html->button("cancel", "core.browser.close_popup()", ["icon" => "fa-times", "@commodal-btn" => "cancel"]);
        $html->submitbutton("Save Changes", false, "parent.requestRefresh", false, false, false, ["icon" => "fa-save", ".btn-success" => true], "core.browser.close_popup();");
        $html->button("Reset Password", "{$this->request->get_panel()}.requestRefresh('?c=system.person.functions/xreset_password/$person->id')", ["icon" => "fa-undo", ".btn-danger" => true, "confirm" => "Are you sure you wish to reset this user's password? A new email with the new password will be sent to the user."]);

        // header
        $html->iradio("Account", "per_is_active", [1 => "Active", 0 => "Deactivated"], $person->per_is_active, [
            "inline" => true,
            "!click" => "{$this->request->get_panel()}.refresh(null, {form:'#{$html->form->id_form}'})",
        ]);

        if ($person->per_is_active == 0) {
            $html->dbinput($person, "per_inactive_reason", ["label" => "Reason for deactivating", "required" => true, "@placeholder" => "Notes / Reason for deactivation"]);
        }
        $html->dbinput($person, "per_firstname", ["required" => true]);
		$html->dbinput($person, "per_lastname", ["required" => true]);
		$html->dbinput($person, "per_tradingname");

		$html->header(3, "Account Details");
		$html->dbinput($person, "per_email", ["required" => true]);

        $html->header(3, "Account");
        $sql = \com\db\sql\select::make();
		$sql->select("acl_code");
		$sql->from("acl_role");

        $html->icheckbox("Roles", "acl_code_arr", \core::db()->selectlist($sql->build(), "acl_code", "acl_code"), array_keys($person->get_role_list()));
    }
    //--------------------------------------------------------------------------------
}





