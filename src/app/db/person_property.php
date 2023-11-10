<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class person_property extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "person_property";
	public $key = "prp_id";
	public $display = "prp_key";
	
    public $display_name = "person_property";
    public $string = "prp_key";

    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		// identification
		"prp_id"					=> array("id"				, "null"	, DB_INT),
		"prp_key"					=> array("key"				, ""		, DB_STRING),
		"prp_value"					=> array("value"			, ""		, DB_TEXT),
		"prp_ref_person"		    => array("person"			, "null"	, DB_REFERENCE, "person"),
		"prp_ref_person_property"	=> array("person property"	, "null"	, DB_REFERENCE, "person_property"),
	);
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return (bool)\core::$app->get_token()->check("users");
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return (bool)\core::$app->get_token()->check("users");
    }
    //--------------------------------------------------------------------------------
    public function on_save(&$obj) {
        $this->encode_obj($obj);
    }

    //--------------------------------------------------------------------------------
	public function get_fromdb($mixed, $options = array()) {
		$obj = parent::get_fromdb($mixed, $options);
		$this->decode_obj($obj, $options);
		return $obj;
	}
    //--------------------------------------------------------------------------------
}