<?php

namespace action\developer\system_email\functions;

/**
 * Class vedit
 * @package action\system\setup\system_user
 * @author Ryno Van Zyl
 */


class xsend implements \com\router\int\action {

	/**
	 * @var \app\ui\set\bootstrap\html
	 */
	protected $html;
    //--------------------------------------------------------------------------------
    use \com\router\tra\action;

    //--------------------------------------------------------------------------------
    public function auth() {
        return \core::$app->get_token()->check("dev");
    }

    //--------------------------------------------------------------------------------
    public function run() {

    	message(false);

    	$this->send_to_option = $this->request->get('send_to_option', \com\data::TYPE_STRING, ["default" => "all"]);
    	$this->subject = $this->request->get('subject', \com\data::TYPE_STRING);
    	$this->queue_30 = (bool)$this->request->get('queue_30', \com\data::TYPE_STRING);
    	$this->message = $this->request->get('message', \com\data::TYPE_HTML);
    	$this->recipients = $this->request->get('recipients', \com\data::TYPE_TEXT);
    	$this->to_default = "User";

    	$recipient_arr = [];

    	switch ($this->send_to_option){
			case "all":
				$this->to_default = "User";
				$recipient_arr = $this->build_db_list();
				break;
			case "admin":
				$this->to_default = "Admin";
				$recipient_arr = $this->build_db_list(ACL_CODE_ADMIN);
				break;
			case "camper":
				$this->to_default = "Camper";
				$recipient_arr = $this->build_db_list(ACL_CODE_CLIENT);
				break;
			case "host":
				$this->to_default = "Host";
				$recipient_arr = $this->build_db_list(ACL_CODE_HOST);
				break;
			case "custom":
				$this->to_default = "User";
				$recipient_arr = $this->build_list_custom();
				break;
		}


		//build body
        $buffer = \com\ui::make()->buffer();
        $buffer->add($this->message)->br()->br();

		\app\email::make()->send($this->subject, $buffer->build(), false, [
			"recipient_arr" => $this->format_arr($recipient_arr),
			"force" => false,
			"delay" => $this->queue_30 ? \com\date::strtodatetime("NOW + 30 minutes") : false,
			"from_name" => "Campfly System",
		]);

		message(true, "Email Queued and will be sent in half an hour");

    }
    //--------------------------------------------------------------------------------
	public function format_arr($recipient_arr = []) {

    	$return_arr = [];

    	foreach ($recipient_arr as $mixed){
    		if(is_array($mixed)){
    			$return_arr[$mixed["per_email"]] = [
    				"email" => $mixed["per_email"],
    				"name" => $mixed["per_name"] ? $mixed["per_name"] : $this->to_default,
    				"type" => "bcc",
				];
			}else{
    			$return_arr[$mixed] = [
    				"email" => $mixed,
    				"name" => $this->to_default,
    				"type" => "bcc",
				];
			}
		}

    	return $return_arr;

	}
	//--------------------------------------------------------------------------------
	public function build_list_custom() {

		$recipient_arr = explode(",", $this->recipients);
		$recipient_arr = array_filter($recipient_arr);

		$count = 0;
		foreach ($recipient_arr as $key => $recipient){
			if($count++ == 5) break;
			$recipient_arr[$key] = str_replace(" ", "", $recipient);
		}

		return $recipient_arr;
	}
    //--------------------------------------------------------------------------------
	public function build_db_list($role = false) {

		$sql = \com\db\sql\select::make();
		$sql->distinct();
		$sql->select("per_email");
		$sql->select(\core::db()->getsql_concat(["per_firstname", "' '", "per_lastname"]). " AS per_name");
		$sql->from("person");
		$sql->from("LEFT JOIN person_role ON (pel_ref_person = per_id)");
		$sql->from("LEFT JOIN acl_role ON (pel_ref_acl_role = acl_id)");
		if($role) $sql->and_where("acl_code = ".dbvalue($role));
		$sql->and_where("per_is_active = 1");
		$sql->and_where("per_email <> ''");

		return \core::db()->select($sql->build());
	}
    //--------------------------------------------------------------------------------
}





