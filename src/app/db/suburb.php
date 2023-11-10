<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class suburb extends \com\core\db\suburb {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "suburb";
	public $key = "sub_id";
	public $display = "sub_name";
	public $parent = "sub_ref_town";

	public $display_name = "suburb";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"sub_id" 				=> array("database id"		, "null"	, DB_KEY),
		"sub_name" 				=> array("name"				, ""		, DB_STRING),
		"sub_name_af" 			=> array("name (Afrikaans)"	, ""		, DB_STRING),
		"sub_ref_town" 			=> array("town"				, "null"	, DB_REFERENCE,		"town"),
		"sub_postal_code" 		=> array("postal code"		, ""		, DB_STRING),
		"sub_residential_code" 	=> array("residential code"	, ""		, DB_STRING),
		"sub_findstring" 		=> array("findstring"		, ""		, DB_STRING),
	);
 	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }

    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
 	//--------------------------------------------------------------------------------
	public function on_delete(&$obj) {

    	\core::db()->query("UPDATE address SET add_ref_suburb = NULL WHERE add_ref_suburb = {$obj->id};");

	}

	//--------------------------------------------------------------------------------
	public function get_slug($obj) {

    	$parts = [];
    	$parts[] = \app\str::str_to_seo($obj->name);
    	$parts[] = $obj->id;

		return implode("_", $parts);
	}
 	//--------------------------------------------------------------------------------
	public function format_name($obj, $format = false, $options = []) {
    	$options = array_merge([
    	    "full_name" => false
    	], $options);

		if($options["full_name"]) {
			$name_parts = [];
			$name_parts[] = $obj->town->province->name;
			$name_parts[] = $obj->town->name;
			$name_parts[] = $obj->name;

			return html_entity_decode(implode(", ", $name_parts));
		}

		return html_entity_decode($obj->name);
	}
	//--------------------------------------------------------------------------------
	public function is_unique($obj) {
		// params
		$obj = $this->splat($obj);

		// sql
		$sql = \com\db\sql\select::make();
		$sql->select("sub_id");
		$sql->from("suburb");
		$sql->and_where("sub_name = ".dbvalue($obj->sub_name));
		$sql->and_where("sub_ref_town = ".dbvalue($obj->sub_ref_town));

		// existing product
		if (!$obj->is_empty("sub_id")) $sql->and_where("sub_id <> ".dbvalue($obj->id));

		// check for unique username
		return !(bool)\core::db()->selectsingle($sql->build());
	}
	//--------------------------------------------------------------------------------
}