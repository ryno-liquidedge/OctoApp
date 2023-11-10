<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class person_person extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "person_person";
    public $key = "pep_id";
    public $display = "pep_type";
    
    public $display_name = "person_person";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "pep_id"                    => array(""         	, "null"        , DB_INT),
        "pep_type"                  => array(""         	, 0             , DB_ENUM),
        "pep_ref_person"            => array("person"   	, "null"        , DB_REFERENCE, "person"),
        "pep_ref_person_link"       => array("link"     	, "null"        , DB_REFERENCE, "person"),
        "pep_old_id"       			=> array("old db id"  	, 0        		, DB_INT),
    );
    
    //--------------------------------------------------------------------------------
    // enums
    //--------------------------------------------------------------------------------
    public $pep_type = [
        0 => "-- Not Selected --",
    ] + PEP_TYPE_ARRAY;

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
}