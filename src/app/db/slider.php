<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class slider extends \com\db\table {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "slider";
    public $key = "sli_id";
    public $display = "sli_caption";
    
    public $display_name = "slider";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "sli_id"                    => array("id",              "null"  , DB_KEY),
        "sli_caption"               => array("Caption",         ""      , DB_STRING),
        "sli_caption_body"          => array("Body",            ""   	, DB_STRING),
        "sli_ref_file_item"         => array("image",           "null"  , DB_REFERENCE, "file_item"),
        "sli_button_label"          => array("Button text",     ""      , DB_STRING),
        "sli_button_link"           => array("Button link",     ""   	, DB_STRING),
        "sli_order"                 => array("order",           0 	    , DB_INT),
        "sli_is_active"             => array("is active",       0 	    , DB_BOOL),
        "sli_type"             		=> array("slider",       	0 	    , DB_ENUM),
    );

    //--------------------------------------------------------------------------------
	public $sli_type = SLIDER_TYPE_ARRAY;
    //--------------------------------------------------------------------------------
    // events
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check("admins");
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check("admins");
    }
    //--------------------------------------------------------------------------------
	public function on_delete_complete(&$obj) {
		if ($obj->file_item) $obj->file_item->delete();
	}
    //--------------------------------------------------------------------------------
    public function on_update_complete(&$obj, &$current_obj) {
        if($current_obj->file_item && $obj->sli_ref_file_item != $current_obj->sli_ref_file_item){
            $current_obj->file_item->delete();
        }
    }
    //--------------------------------------------------------------------------------
}
