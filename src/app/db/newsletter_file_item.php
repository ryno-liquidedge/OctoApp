<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class newsletter_file_item extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "newsletter_file_item";
    public $key = "nfi_id";
    public $display = "nfi_ref_newsletter";

    public $display_name = "newsletter";


    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "nfi_id"						=> array("id"					, "null"	, DB_INT),
        "nfi_ref_newsletter"			=> array("newsletter"			, "null"	, DB_REFERENCE, "newsletter"),
        "nfi_ref_file_item"				=> array("file_item"			, "null"	, DB_REFERENCE, "file_item"),
        "nfi_ref_file_item_original"	=> array("file_item_original"	, "null"	, DB_REFERENCE, "file_item"),
        "nfi_is_enabled"				=> array("is enabled"			, 0			, DB_BOOL),
        "nfi_is_main_image"				=> array("is main image"		, 0			, DB_BOOL),
        "nfi_youtube_link"				=> array("youtube link"		    , ""	    , DB_STRING),
        "nfi_remote_id"					=> array("remote id"			, 0			, DB_INT),
        "nfi_old_id"					=> array("old db id"			, 0			, DB_INT),
    );
	//--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
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
            $current_obj->file_item_original->delete();
        }
    }
    //--------------------------------------------------------------------------------

}