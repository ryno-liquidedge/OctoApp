<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class country extends \com\core\db\country {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
	public $name = "country";
	public $key = "con_id";
	public $display = "con_name";
	public $string = "con_code";

	public $display_name = "country";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"con_id" 					=> array("database id"		, "null"		, DB_KEY),
		"con_name" 					=> array("name"				, ""			, DB_STRING),
        "con_code" 					=> array("code"				, ""			, DB_STRING),
        "con_code_alpha_3" 			=> array("alpha 3 code"		, ""			, DB_STRING),
        "con_dial_code" 			=> array("dial code"		, ""			, DB_STRING),
        "con_order" 				=> array("order"			, 0				, DB_INT),
	);
 	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
		return \core::$app->get_token()->check('users');
	}
    //--------------------------------------------------------------------------------
	public function on_auth_use(&$obj, $user, $role) {
		return \core::$app->get_token()->check('users');
	}
 	//--------------------------------------------------------------------------------
	public static function get_country_list() {
		return \core::dbt("country")->get_list("con_code IN ('ZA', 'NA')");
	}
 	//--------------------------------------------------------------------------------
}