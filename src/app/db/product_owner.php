<?php

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class product_owner extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "product_owner";
    public $key = "pow_id";
    public $display = "pow_ref_product";
    
    public $display_name = "product_owner";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "pow_id"                => array("id",          "null",         DB_INT),
        "pow_ref_product"       => array("product",     "null",         DB_REFERENCE, "product"),
        "pow_ref_person"      	=> array("person",     	"null",         DB_REFERENCE, "person"),
        "pow_remote_id"      	=> array("remote id",  	0,         		DB_INT),
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