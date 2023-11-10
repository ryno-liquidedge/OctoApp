<?php

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class product_file_item extends \com\db\table {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "product_file_item";
	public $key = "pfi_id";
	public $display = "pfi_ref_product";
	public $parent = "pfi_ref_product";
	public $display_name = "brand file item";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"pfi_id"				     => array("id"		        , "null", DB_INT),
		"pfi_ref_product"           => array("product"	        , "null", DB_REFERENCE,	"product"),
		"pfi_ref_file_item"		     => array("file"		    , "null", DB_REFERENCE,	"file_item"),
		"pfi_ref_file_item_original" => array("original file"   , "null", DB_REFERENCE,	"file_item"),
		"pfi_type"		             => array("type"		    , 0     , DB_ENUM),
	);
	//--------------------------------------------------------------------------------
    public $pfi_type = [
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