<?php

namespace action\website\index\functions;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class xresend_verification_email implements \com\router\int\action {

    /**
     * @var \db_person
     */
    protected $person;

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {
		$this->id = $this->request->get('id', \com\data::TYPE_STRING, ["get" => true]);
	    $this->person = \core::dbt("person")->get_fromdb("MD5(per_id) = ".dbvalue($this->id));

        //send account verification email
        \app\email::make()->send_account_verification_email($this->person);

        return "stream";
	}
    //--------------------------------------------------------------------------------
}

