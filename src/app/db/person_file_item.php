<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class person_file_item extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
    public $name = "person_file_item";
	public $key = "pfi_id";
	public $display = "pfi_id";

	public $display_name = "Person File Item";
 	//--------------------------------------------------------------------------------
	// functions
    //--------------------------------------------------------------------------------
    public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
	 	// identification
		"pfi_id" 						=> array("database id"				, "null"	, DB_INT),
        "pfi_ref_person"                => array("person"                   , "null"	, DB_REFERENCE		, "person"),
        "pfi_ref_file_item"             => array("file item"                , "null"	, DB_REFERENCE		, "file_item"),
        "pfi_ref_file_item_original" 	=> array("file item"                , "null"	, DB_REFERENCE		, "file_item"),
        "pfi_type"                      => array("file item"                , 0	        , DB_ENUM),
        "pfi_old_id"                 	=> array("old db id"                , 0	        , DB_INT),
	);
    //--------------------------------------------------------------------------------
    public $pfi_type = [
        0 => "-- Not Selected --",
        ASSET_IMAGE_PROFILE => "Profile Picture",
    ];
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check("users");
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check("users");
    }
    //--------------------------------------------------------------------------------
    public function on_delete_complete(&$obj) {
		if($obj->file_item) $obj->file_item->delete();
		if($obj->file_item_original) $obj->file_item_original->delete();
	}
    //--------------------------------------------------------------------------------
    public function on_update_complete(&$obj, &$current_obj) {
        if($current_obj->file_item && $obj->nfi_ref_file_item != $current_obj->nfi_ref_file_item){
            $current_obj->file_item->delete();
            $current_obj->nfi_ref_file_item_original->delete();
        }
    }
	//--------------------------------------------------------------------------------
}
