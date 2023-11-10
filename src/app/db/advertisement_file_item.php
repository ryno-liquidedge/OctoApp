<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class advertisement_file_item extends \com\db\table {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "advertisement_file_item";
    public $key = "afi_id";
    public $display = "afi_file_type";

    public $display_name = "advertisement_file_item";

    public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        "afi_id"		                    => array("id"			        , "null"    , DB_INT),
        "afi_file_type"		                => array("file type"		    , 0	        , DB_ENUM),
        "afi_ref_file_item"		            => array("file item"		    , "null"	, DB_REFERENCE, "file_item"),
        "afi_ref_file_item_original"		=> array("file item original" 	, "null"	, DB_REFERENCE, "file_item"),
        "afi_ref_advertisement"		        => array("advertisement"        , "null"	, DB_REFERENCE, "advertisement"),
    );

    //--------------------------------------------------------------------------------
    public $afi_file_type = ASSET_ARRAY;
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
    //--------------------------------------------------------------------------------
    public function on_update_complete(&$obj, &$current_obj) {

        if($obj->afi_ref_file_item != $current_obj->afi_ref_file_item) $current_obj->file_item->delete();
        if($obj->afi_ref_file_item_original != $current_obj->afi_ref_file_item_original) $current_obj->file_item_original->delete();

    }
    //--------------------------------------------------------------------------------
    public function on_delete_complete(&$obj) {
		if($obj->file_item) $obj->file_item->delete();
		if($obj->file_item_original) $obj->file_item_original->delete();
	}
	//--------------------------------------------------------------------------------
}