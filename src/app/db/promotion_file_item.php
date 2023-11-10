<?php

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class promotion_file_item extends \com\db\table {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "promotion_file_item";
	public $key = "prf_id";
	public $display = "prf_ref_promotion";

	public $parent = "prf_ref_promotion";
	public $display_name = "promotion file item";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"prf_id"				     => array("id"		        , "null", DB_INT),
		"prf_ref_promotion"           => array("promotion"	    , "null", DB_REFERENCE,	"promotion"),
		"prf_ref_file_item"		     => array("file"		    , "null", DB_REFERENCE,	"file_item"),
		"prf_ref_file_item_original" => array("original file"   , "null", DB_REFERENCE,	"file_item"),
		"prf_type"		             => array("type"		    , 0     , DB_ENUM),
	);
	//--------------------------------------------------------------------------------
    public $prf_type = [
        0 => "-- Not Selected --",
        ASSET_MAIN_IMAGE => "Main Image",
    ];
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
    public function on_delete_complete(&$obj){
        if($obj->file_item) $obj->file_item->delete();
    }
    //--------------------------------------------------------------------------------
}