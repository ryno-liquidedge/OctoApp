<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class town extends \com\core\db\town {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "town";
	public $key = "tow_id";
	public $display = "tow_name";
	public $parent = "tow_ref_province";

	public $display_name = "town";

	public $field_arr = array(	// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"tow_id" 			=> array("database id"		, "null"	, DB_KEY),
		"tow_name" 			=> array("name"				, ""		, DB_STRING),
		"tow_name_af" 		=> array("name (afrikaans)"	, ""		, DB_STRING),
		"tow_ref_province" 	=> array("province"			, "null"	, DB_REFERENCE,		"province"),
        "tow_ref_country"   => array("country"          , "null"    , DB_REFERENCE,		"country"),
		"tow_latitude" 		=> array("latitude"			, ""		, DB_STRING),
		"tow_longitude" 	=> array("longitude"		, ""		, DB_STRING),
	);
 	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return true;
    }

    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return true;
    }
    //--------------------------------------------------------------------------------
	public function on_delete(&$obj) {
    	message(false);

    	//update all addresses
		\core::db()->query("UPDATE address SET add_ref_town = NULL WHERE add_ref_town = {$obj->id};");

		$suburb_arr = \core::dbt("suburb")->get_fromdb("sub_ref_town = ".dbvalue($obj->id), ["multiple" => true]);
		foreach ($suburb_arr as $suburb){
			$suburb->delete();
		}

    	message(true);
	}
 	//--------------------------------------------------------------------------------
    public function format_name($obj, $format = false, $options = []) {

    	$options = array_merge([
    	    "include_province" => true,
    	    "include_country" => true,
    	], $options);

        $return_arr = [];

        $return_arr[] = $obj->name;
        if($options["include_province"] && $obj->province) $return_arr[] = $obj->province->name;
        if($options["include_country"] && $obj->country) $return_arr[] = $obj->country->name;

        return implode(", ", $return_arr);

    }
    //--------------------------------------------------------------------------------
	public function is_unique($obj) {
		// params
		$obj = $this->splat($obj);

		// sql
		$sql = \com\db\sql\select::make();
		$sql->select("tow_id");
		$sql->from("town");
		$sql->and_where("tow_name = ".dbvalue($obj->tow_name));
		$sql->and_where("tow_ref_province = ".dbvalue($obj->tow_ref_province));

		// existing product
		if (!$obj->is_empty("tow_id")) $sql->and_where("tow_id <> ".dbvalue($obj->id));

		// check for unique username
		return !(bool)\core::db()->selectsingle($sql->build());
	}
 	//--------------------------------------------------------------------------------
}