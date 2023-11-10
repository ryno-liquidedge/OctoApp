<?php

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class product_property extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "product_property";
    public $key = "pdp_id";
    public $display = "pdp_value";

    public $display_name = "Product Property";

    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "pdp_id"                        => array("id",                  "null",         DB_INT),
        "pdp_display"                   => array("display",             "",             DB_STRING),
        "pdp_key"                       => array("key",                 "",             DB_STRING),
        "pdp_value"                     => array("value",               "",             DB_TEXT),
        "pdp_ref_product"               => array("product",             "null",         DB_REFERENCE, "product"),
        "pdp_ref_person"				=> array("person",				"null",         DB_REFERENCE, "person"),
        "pdp_remote_person_id"          => array("remote person id",    0,              DB_INT),
        "pdp_remote_id"                 => array("remote id",           0,				DB_INT),
        "pdp_is_custom"                 => array("is custom",           0,              DB_BOOL),
        "pdp_is_active"                 => array("is active",           0,              DB_BOOL),
        "pdp_is_editable"               => array("is editable",         0,              DB_BOOL),
        "pdp_ref_product_property"      => array("parent property",     "null",         DB_REFERENCE, "product_property"),
        "pdp_last_updated"              => array("last updated",        "null",         DB_DATETIME),
        "pdp_date_created"              => array("date created",        "null",         DB_DATETIME),
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
    public function get_fromdb($mixed, $options = array()) {

        $obj = parent::get_fromdb($mixed, $options);

        $this->decode_obj($obj, $options);

        return $obj;
    }
    //--------------------------------------------------------------------------------
    public function on_insert(&$obj) {
        parent::on_insert($obj);
        if(!\com\user::$active_id) $this->audit = false;

        $obj->pdp_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
    }
    //--------------------------------------------------------------------------------
    public function on_delete(&$obj) {
        parent::on_delete($obj);
        if(!\com\user::$active_id) $this->audit = false;

        $sub_product_property_arr = \core::dbt("product_property")->get_fromdb("pdp_ref_product_property = $obj->id", ["multiple" => true]);
        if($sub_product_property_arr){
            foreach ($sub_product_property_arr as $sub_product_property) $sub_product_property->delete();
        }

    }
    //--------------------------------------------------------------------------------
    public function on_save(&$obj) {
        if(!\com\user::$active_id) $this->audit = false;

        $solid_class = \app\solid::get_instance($obj->pdp_key);
        $obj->pdp_display = $solid_class->get_display_name();

        $this->encode_obj($obj);

        if($obj->is_empty("pdp_last_updated"))
            $obj->pdp_last_updated = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();

    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $product_property
	 * @return false
	 */
	public function can_override_octo_value($product_property) {
		$solid_class = \app\solid::get_instance($product_property->pdp_key);
		return $solid_class->allow_external_override();
	}
    //--------------------------------------------------------------------------------
    public function parse_value($product_property) {
	    $solid_class = \app\solid::get_instance($product_property->pdp_key);
		return $solid_class->parse($product_property->pdp_value);
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $product_property
	 * @return mixed
	 */
    public function get_data_type($product_property) {
        $solid_class = \app\solid::get_instance($product_property->pdp_key);
		return $solid_class->get_data_type();
    }
	//--------------------------------------------------------------------------------
}
