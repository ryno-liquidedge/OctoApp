<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class file_data extends \com\core\db\file_data {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
	public $name = "file_data";
	public $key = "fid_id";
	public $display = "fid_id";

	public $display_name = "file data";
	public $audit = false;

	public $field_exclude_arr = ["fid_data"];
	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"fid_id"			=> array("id"			, "null", DB_KEY),
		"fid_data"			=> array("data"			, "null", DB_TEXT),
	);
 	//--------------------------------------------------------------------------------
}