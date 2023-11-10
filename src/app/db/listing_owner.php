<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class listing_owner extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "listing_owner";
    public $key = "lio_id";
    public $display = "lio_ref_listing";

    public $display_name = "listing_owner";

    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "lio_id"                => array("id",          "null",         DB_INT),
        "lio_ref_listing"       => array("listing",     "null",         DB_REFERENCE, "listing"),
        "lio_ref_person"      	=> array("person",     	"null",         DB_REFERENCE, "person"),
        "lio_remote_id"      	=> array("remote id",  	0,         		DB_INT),
    );
    
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