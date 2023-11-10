<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class featured_categories extends \com\db\table {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "featured_categories";
	public $key = "fec_id";
	public $display = "fec_heading";

	public $display_name = "Featured Categories";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
	 	// identification
		"fec_id" 						=> array("database id",         "null",     DB_KEY),
		"fec_heading"                   => array("heading",             "",         DB_STRING),
        "fec_description"               => array("description",         "",         DB_TEXT),
        "fec_link"                      => array("link",                "",         DB_STRING),
        "fec_ref_file_item"             => array("file item",           "null",     DB_REFERENCE, "file_item"),
        "fec_is_enabled"                => array("is enabled",          0,          DB_BOOL),
        "fec_order"                     => array("order",               0,          DB_INT),
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
}