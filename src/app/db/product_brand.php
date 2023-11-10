<?php

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class product_brand extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "product_brand";
    public $key = "pbr_id";
    public $display = "pbr_ref_product";
    
    public $display_name = "product_brand";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "pbr_id"                => array("id",          "null",         DB_INT),
        "pbr_ref_product"       => array("product",     "null",         DB_REFERENCE, "product"),
        "pbr_ref_brand"         => array("brand",       "null",         DB_REFERENCE, "brand"),
    );
    
    //--------------------------------------------------------------------------------
    // events
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return (bool)\core::$app->get_token()->check("users");
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return (bool)\core::$app->get_token()->check("users");
    }
    //--------------------------------------------------------------------------------
}