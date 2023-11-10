<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class listing_asset extends \com\core\db\acl_role {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------

 	public $name = "listing_asset";
	public $key = "lia_id";
	public $display = "lia_name";

	public $display_name = "listing_asset";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"lia_id"		           	 		=> array("id"			        	, "null"        , DB_KEY),

		"lia_name"		            		=> array("name"			        	, ""            , DB_STRING),
		"lia_title"		            		=> array("title"			    	, ""            , DB_STRING),
		"lia_description"		    		=> array("description"		    	, ""            , DB_TEXT),
		"lia_link"		            		=> array("link"			        	, ""            , DB_STRING),
		"lia_video_id"		        		=> array("video id"			    	, ""            , DB_STRING),
		"lia_order"		            		=> array("order"		        	, 0             , DB_INT),

		"lia_ref_file_item"	        		=> array("file item"		    	, "null"        , DB_REFERENCE, "file_item"),
		"lia_ref_file_item_original"		=> array("file item original"		, "null"        , DB_REFERENCE, "file_item"),
		"lia_ref_listing"	        		=> array("listing"	            	, "null"        , DB_REFERENCE, "listing"),

		"lia_date_created"		    		=> array("date created"	        	, "null"        , DB_DATETIME),
		"lia_type"		        		    => array("type"		        		, 0             , DB_ENUM),
		"lia_old_id"		        		=> array("old db id"		    	, 0             , DB_INT),
	);
	//--------------------------------------------------------------------------------
	public $lia_type = [
		ASSET_LINK => "General Link",
		ASSET_YOUTUBE => "Youtube Video",
		ASSET_VIRTUAL_TOUR => "Virtual Tour",
		ASSET_MAIN_IMAGE => "Main Image",
		ASSET_GALLERY_IMAGE => "Gallery Image",
		ASSET_DOCUMENT => "Documents",
		ASSET_FLOORPLAN => "Floor plans",
	];
	//--------------------------------------------------------------------------------
	// events
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
	//--------------------------------------------------------------------------------
	public function auth_for($obj, $user = false, $role = false, $options = []) {
		return true;
	}
    //--------------------------------------------------------------------------------
	public function on_insert(&$obj) {
		$obj->lia_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
	}
    //--------------------------------------------------------------------------------
	public function on_delete_complete(&$obj) {
		if($obj->file_item) $obj->file_item->delete();
		if($obj->file_item_original) $obj->file_item_original->delete();
	}

	//--------------------------------------------------------------------------------
    public function on_update_complete(&$obj, &$current_obj) {
        if($current_obj->file_item && $obj->lia_ref_file_item != $current_obj->lia_ref_file_item){
            $current_obj->file_item->delete();
        }

        if($current_obj->file_item_original && $obj->lia_ref_file_item_original != $current_obj->lia_ref_file_item_original){
            $current_obj->file_item_original->delete();
        }
    }

    //--------------------------------------------------------------------------------
	public function on_save(&$obj) {
		if($obj->file_item_original){
			$obj->lia_name = $obj->file_item_original->fil_name;
		}
	}
	//--------------------------------------------------------------------------------
	public function on_delete(&$obj) {
		if($obj->lia_type == ASSET_VIRTUAL_TOUR){
    		$obj->listing->delete_prop(LISTING_PROPERTY_VIRTUAL_TOUR, [
    			".lip_value" => $obj->lia_link
			]);
		}
	}

	//--------------------------------------------------------------------------------
	public function on_save_complete(&$obj) {

    	if($obj->lia_type == ASSET_VIRTUAL_TOUR){
    		$obj->listing->save_property(LISTING_PROPERTY_VIRTUAL_TOUR, $obj->lia_link);
		}

	}
	//--------------------------------------------------------------------------------
	public function set_as_main_asset($listing_asset) {
    	$listing_asset_arr = \core::dbt("listing_asset")->get_fromdb("lia_ref_listing = ".dbvalue($listing_asset->lia_ref_listing)." AND lia_type = ".dbvalue(ASSET_MAIN_IMAGE), ["multiple" => true]);
    	foreach ($listing_asset_arr as $listing_asset_main){
    		$listing_asset_main->lia_type = ASSET_GALLERY_IMAGE;
    		$listing_asset_main->update();
		}

    	$listing_asset->lia_type = ASSET_MAIN_IMAGE;
    	$listing_asset->update();
	}
	//--------------------------------------------------------------------------------
	public function format_name($listing_asset, $format = false, $options = []) {

    	$title = $listing_asset->lia_name;
    	if(!$listing_asset->is_empty("lia_link")) $title = $listing_asset->lia_link;
    	if(!$listing_asset->is_empty("lia_title")) $title = $listing_asset->lia_title;

    	return $title;
	}
	//--------------------------------------------------------------------------------
}