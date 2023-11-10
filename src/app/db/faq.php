<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class faq extends \com\db\table {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "faq";
	public $key = "faq_id";
	public $display = "faq_question";

	public $display_name = "FAQ";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
	 	// identification
		"faq_id" 						=> array("database id"			, "null"    , DB_KEY),
		"faq_question" 					=> array("question"				, ""	    , DB_STRING),
		"faq_answer" 					=> array("answer"				, ""	    , DB_TEXT),
		"faq_type" 						=> array("type"					, 0	    	, DB_ENUM),
	);
	//--------------------------------------------------------------------------------
	public $faq_type = [
		0 => "-- Not Selected --",
		1 => "General",
	];
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
}