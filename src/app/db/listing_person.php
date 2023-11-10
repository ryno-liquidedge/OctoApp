<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class listing_person extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "listing_person";
    public $key = "lpe_id";
    public $display = "lpe_ref_listing";
    
    public $display_name = "listing_person";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "lpe_id"                => array("id",          "null",         DB_INT),
        "lpe_ref_listing"       => array("product",     "null",         DB_REFERENCE, "listing"),
        "lpe_ref_person"      	=> array("person",     	"null",         DB_REFERENCE, "person"),
        "lpe_type"      		=> array("type",     	0,         		DB_ENUM),
        "lpe_remote_id"      	=> array("remote id",  	0,         		DB_INT),
    );
    
    //--------------------------------------------------------------------------------
	public $lpe_type = [0 => "-- Not Selected --"] + LISTING_PERSON_ARR;
    //--------------------------------------------------------------------------------
    // events
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check("users");
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check("users");
    }
	//--------------------------------------------------------------------------------
}