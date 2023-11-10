<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class listing_property extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "listing_property";
	public $key = "lip_id";
	public $display = "lip_value";

    public $display_name = "listing_property";
    public $string = "lip_key";

    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		// identification
		"lip_id"					=> array("id"				, "null"	, DB_INT),
		"lip_key"					=> array("key"				, ""		, DB_STRING),
		"lip_value"					=> array("value"			, ""		, DB_TEXT),
		"lip_ref_listing"		    => array("listing"			, "null"	, DB_REFERENCE, "listing"),
		"lip_ref_listing_property"	=> array("listing property"	, "null"	, DB_REFERENCE, "listing_property"),
		"lip_remote_id"             => array("remote id"       	, 0	        , DB_INT),
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