<?php

namespace action\developer\system_email;

/**
 * Class vedit
 * @package action\system\setup\system_user
 * @author Ryno Van Zyl
 */


class vsend implements \com\router\int\action {

	/**
	 * @var \app\ui\set\bootstrap\html
	 */
	protected $html;
    //--------------------------------------------------------------------------------
    use \com\router\tra\action;

    //--------------------------------------------------------------------------------
    public function auth() {
        return \core::$app->get_token()->check("ui_users");
    }

    //--------------------------------------------------------------------------------
    public function run() {

    	$this->send_to_option = $this->request->get('send_to_option', \com\data::TYPE_STRING, ["default" => "all"]);
    	$this->subject = $this->request->get('subject', \com\data::TYPE_STRING);
    	$this->message = $this->request->get('message', \com\data::TYPE_HTML);
    	$this->recipients = $this->request->get('recipients', \com\data::TYPE_TEXT);
    	$this->queue_30 = (bool) $this->request->get('queue_30', \com\data::TYPE_STRING);

        // html
        $this->html = \com\ui::make()->html();
        $this->html->header(2, "Send System Email");
        $this->html->form("?c=developer.system_email.functions/xsend");
        $this->html->submitbutton("Save Changes", false, "requestRefresh", false, "Are you sure you want to continue?", false, ["icon" => "fa-save", ".btn-success" => true], "core.browser.close_popup();");

        $this->html->column();
        $this->html->icheckbox("", "queue_30", [1 => "Queue for 30 mins"], $this->queue_30 ? 1 : false);
        $this->html->iselect("Send To", "send_to_option", [
        	"all" => "All",
        	"admin" => "Admin",
        	"camper" => "Campers",
        	"host" => "Hosts",
        	"custom" => "Custom",
		], $this->send_to_option, ["!change" => "{$this->request->get_panel()}.refresh(null, {form:'#{$this->html->form->id_form}', no_overlay:true})", "label_col" => 3]);
        if($this->send_to_option == "custom"){
        	$this->html->itextarea("Recipients", "recipients", $this->recipients, 10, ["required" => true, "@placeholder" => "Comma Separated Emails"]);
		}

        $this->html->itext("Subject", "subject", $this->subject, ["required" => true]);
        $this->html->itextarea("Message", "message", $this->message, 10, ["required" => true, "wysiwyg" => true]);


    }
    //--------------------------------------------------------------------------------
}





