<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class address extends \com\core\db\address {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------

	public $name = "address";
	public $key = "add_id";
	public $display = "add_name";
	public $parent = "add_ref_person";
	public $display_name = "address";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"add_id"				=> array("id"				, "null", DB_KEY),
		"add_name"				=> array("name"				, ""	, DB_STRING),
		"add_type"				=> array("type"				, 0		, DB_ENUM),
		"add_nomination"		=> array("nomination"		, 0		, DB_ENUM),

		"add_ref_suburb"		=> array("suburb"			, "null", DB_REFERENCE, "suburb"),
		"add_ref_town"			=> array("town"				, "null", DB_REFERENCE, "town"),
		"add_ref_province"		=> array("province"			, "null", DB_REFERENCE, "province"),
		"add_ref_country"		=> array("country"			, "null", DB_REFERENCE, "country"),

		"add_unitnr"			=> array("unitnr"			, ""	, DB_STRING),
		"add_floor"				=> array("floor"			, ""	, DB_STRING),
		"add_building"			=> array("building"			, ""	, DB_STRING),
		"add_farm"				=> array("farm"				, ""	, DB_STRING),
		"add_streetnr"			=> array("streetnr"			, ""	, DB_STRING),
		"add_street"			=> array("street"			, ""	, DB_STRING),
		"add_development"		=> array("development"		, ""	, DB_STRING),
		"add_attention"			=> array("attention"		, ""	, DB_STRING),
		"add_pobox"				=> array("P.O. Box"			, ""	, DB_STRING),
		"add_postnet"			=> array("postnet"			, ""	, DB_STRING),
		"add_privatebag"		=> array("privatebag"		, ""	, DB_STRING),
		"add_clusterbox"		=> array("cluster box"		, ""	, DB_STRING),
		"add_line1"				=> array("line1"			, ""	, DB_STRING),
		"add_line2"				=> array("line2"			, ""	, DB_STRING),
		"add_line3"				=> array("line3"			, ""	, DB_STRING),
		"add_line4"				=> array("line4"			, ""	, DB_STRING),
		"add_code"				=> array("code"				, ""	, DB_STRING),
		"add_ref_address"		=> array("address"			, "null", DB_REFERENCE, "address"),
		"add_raw"				=> array("raw address"		, ""	, DB_TEXT),
		"add_gps_lat"			=> array("gps latitude"		, ""	, DB_STRING),
		"add_gps_lng"			=> array("gps longitude"	, ""	, DB_STRING),

		// related references
		// this is where you add additional references
		// add the same reference to the $this->related_reference_arr property
		"add_ref_person"		=> array("person"		, "null", DB_REFERENCE, "person"),
		"add_ref_listing"		=> array("listing"		, "null", DB_REFERENCE, "listing"),
		"add_remote_id"		    => array("remote id"	, 0		, DB_INT),
	);
	//--------------------------------------------------------------------------------
	public $add_nomination = [
		0 => "-- Not Selected --",
		1 => "Residential",
		2 => "Postal",
		3 => "Work",
	];
	//--------------------------------------------------------------------------------
	public $add_type = [
		0 => "-- Not Selected --",
		1 => "Physical",
		2 => "PO. Box",
		3 => "Postnet suite / Private Bag",
		6 => "Cluster Box",
		4 => "Copy residential address",
		5 => "International",
	];
	//--------------------------------------------------------------------------------
	public $related_reference_arr = [
		"add_ref_person",
	];
 	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\db\table $obj
	 * @param \db_person $user
	 * @param string $role
	 * @return bool|void
	 */
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param \com\db\table $obj
	 * @param \db_person $user
	 * @param string $role
	 * @return bool|void
	 */
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
	//--------------------------------------------------------------------------------
	public function on_insert(&$address) {
        if($address->add_type == 0) $address->add_type = 1;
        if($address->add_nomination == 0) $address->add_nomination = 1;
	}
	//--------------------------------------------------------------------------------
	public function on_save(&$address) {

        if($address->add_type == 0) $address->add_type = 1;
        if($address->add_nomination == 0) $address->add_nomination = 1;

        $address->save_address_reference("country");
        if($address->country) $address->save_address_reference("province",
            [".prv_ref_country" => $address->country->id],
        );
        if($address->country && $address->province) $address->save_address_reference("town",
            [".tow_ref_country" => $address->country->id],
            [".tow_ref_province" => $address->province->id],
        );

	    if($address->town) $address->save_address_reference("suburb",
            [".sub_ref_town" => $address->town->id],
        );

		// name
		$address->add_name = "{$address->nomination} ({$address->type})";

		// copy address
		if ($address->add_type == 4 && $address->address && $address->address->add_type != 4) {
			$address->copy($address->address);
		}

		// populate lines
		if (in_array($address->add_type, [1,2,3,4])) {
			$address_arr = $address->format_lines();
			$address->add_line1 = $address_arr[0];
			$address->add_line2 = $address_arr[1];
			$address->add_line3 = $address_arr[2];
			$address->add_line4 = $address_arr[3];
		}

		$parts = [];
		if(!$address->is_empty("add_line1")) $parts[] = $address->add_line1;
		if(!$address->is_empty("add_line2")) $parts[] = $address->add_line2;
		if($parts) $address->add_street = implode(", ", $parts);

	}
	//--------------------------------------------------------------------------------
    public function save_address_reference($address, $table, $find_arr = [], $options = []) {

	    $options = array_merge([
	        "overwrite_data_arr" => []
	    ], $options);

	    $dbt = \core::dbt($table);
	    $find_arr = \com\arr::splat($find_arr);

	    if(property_exists($address, "__{$table}"))
            $find_arr[".{$dbt->get_prefix()}_name"] = $address->{"__{$table}"};

	    if(!isset($find_arr[".{$dbt->get_prefix()}_name"]) || !$find_arr[".{$dbt->get_prefix()}_name"]) return false;

	    $sql = \app\db\sql\select::make();
	    $sql->select("{$dbt->name}.*");
	    $sql->from($dbt->name);
	    $sql->extract_options($find_arr);
	    $sql->extract_options($options);
	    $obj = $dbt->get_fromsql($sql->build());

	    if(!$obj){

	        $obj = \core::dbt($table)->get_fromdefault();

	        // extract the fields from options
            $field_arr = \com\arr::extract_signature_items(".", $find_arr);
            foreach ($field_arr as $field_index => $field_item) {
                $obj->{$field_index} = $field_item;
            }

            // extract the fields from options
            $field_arr = \com\arr::extract_signature_items(".", $options);
            foreach ($field_arr as $field_index => $field_item) {
                $obj->{$field_index} = $field_item;
            }

	        foreach ($options["overwrite_data_arr"] as $field => $value){
                $obj->{$field} = $value;
            }

	        if($obj->is_empty("{$dbt->get_prefix()}_name")) return false;

	        $obj->save();
        }

        $address->{"add_ref_{$table}"} = $obj->id;
        $address->{$table} = $obj;

        return $obj;
    }
	//--------------------------------------------------------------------------------

	/**
	 * @param $address
	 * @param array $options
	 * @return bool|db_province
	 */
	public function get_province($address, $options = []) {
		$options = array_merge([
		    "field" => false,
		], $options);

		if(!$address->town)
			$address->town = \core::dbt("province")->splat($address->add_ref_province);

		if($options["field"]){
			if($address->province) return $address->province->{$options["field"]};
			return false;
		}

		return $address->province;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $address
	 * @param array $options
	 * @return bool|db_town
	 */
	public function get_town($address, $options = []) {
		$options = array_merge([
		    "field" => false,
		], $options);

		if(!$address->town)
			$address->town = \core::dbt("town")->splat($address->add_ref_town);

		if($address->town && $options["field"]){
			if($address->town) return $address->town->{$options["field"]};
			return false;
		}

		return $address->town;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $address
	 * @param array $options
	 * @return bool|db_suburb
	 */
	public function get_suburb($address, $options = []) {
		$options = array_merge([
		    "field" => false,
		], $options);

		if(!$address->suburb)
			$address->suburb = \core::dbt("suburb")->splat($address->add_ref_suburb);

		if($options["field"]){
			if($address->suburb) return $address->suburb->{$options["field"]};
			return false;
		}

		return $address->suburb;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $address
	 * @param array $options
	 * @return bool|db_country
	 */
	public function get_country($address, $options = []) {
		$options = array_merge([
		    "field" => false,
		], $options);

		if(!$address->country)
			$address->country = \core::dbt("country")->splat($address->add_ref_country);

		if($options["field"]){
			if($address->country) return $address->country->{$options["field"]};
			return false;
		}

		return $address->country;
	}
	//--------------------------------------------------------------------------------
	public function format_name($address, $options = []) {

		$options = array_merge([
		    "separator" => ", "
		], $options);

		$address_lines_arr = $address->format_lines();
		$address_lines_arr = array_filter($address_lines_arr);

		return $address_lines_arr ? implode($options["separator"], $address_lines_arr) : $address->{$this->display};
	}
 	//--------------------------------------------------------------------------------
	public function parse_request($address) {
		// params
		$address = $this->splat($address);

		// get values
		$OPTIONS = ["index" => $address->id];

		// copy address
		if ($address->add_type == 4) {
			// check that we have postal nomination
			if ($address->add_nomination == 1) return \com\error::create("Invalid copy source nomination");

			$related_field = $address->get_related_field();
			$related_field_db = substr($related_field, 8);
			$residential_address = $address->{$related_field_db}->get_address(1);
			$address->add_ref_address = $residential_address->id;

			// return all the changes that were made
			return $address->get_changes();
		}

		// references
		$address->add_ref_suburb = \core::$app->get_request()->get("add_ref_suburb", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_REFERENCE, $OPTIONS);
		$address->add_ref_town = \core::$app->get_request()->get("add_ref_town", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_REFERENCE, $OPTIONS);
		$address->add_ref_province = \core::$app->get_request()->get("add_ref_province", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_REFERENCE, $OPTIONS);
		$address->add_ref_country = \core::$app->get_request()->get("add_ref_country", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_REFERENCE, $OPTIONS);

		// basic
		$address->add_unitnr = \core::$app->get_request()->get("add_unitnr", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ALPHANUM, $OPTIONS);
		$address->add_floor = \core::$app->get_request()->get("add_floor", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ALPHANUM, $OPTIONS);
		$address->add_building = \core::$app->get_request()->get("add_building", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_farm = \core::$app->get_request()->get("add_farm", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_streetnr = \core::$app->get_request()->get("add_streetnr", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ALPHANUM, $OPTIONS);
		$address->add_street = \core::$app->get_request()->get("add_street", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_development = \core::$app->get_request()->get("add_development", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_attention = \core::$app->get_request()->get("add_attention", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_pobox = \core::$app->get_request()->get("add_pobox", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ALPHANUM, $OPTIONS);
		$address->add_postnet = \core::$app->get_request()->get("add_postnet", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ALPHANUM, $OPTIONS);
		$address->add_privatebag = \core::$app->get_request()->get("add_privatebag", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ALPHANUM, $OPTIONS);
		$address->add_clusterbox = \core::$app->get_request()->get("add_clusterbox", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_line1 = \core::$app->get_request()->get("add_line1", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_line2 = \core::$app->get_request()->get("add_line2", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_line3 = \core::$app->get_request()->get("add_line3", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_code = \core::$app->get_request()->get("add_code", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ALPHANUM, $OPTIONS);
		$address->add_gps_lat = \core::$app->get_request()->get("add_gps_lat", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);
		$address->add_gps_lng = \core::$app->get_request()->get("add_gps_lng", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $OPTIONS);

		$fn_parse_google_places_values = function($places_id, $field, $type, $options = []) use(&$address){

			$value = \core::$app->get_request()->get($places_id, $type, $options);
			if(!$value) return;

			message(false);
			switch ($field){
				case "add_ref_suburb":
					$find_arr = [];
					$find_arr["create"] = true;
					$find_arr[".sub_name"] = $value;
					if(!$address->is_empty("add_ref_town")) $find_arr[".sub_ref_town"] = $address->add_ref_town;

					$suburb = \core::dbt("suburb")->find($find_arr);
					if($suburb->source != "database"){
						$suburb->sub_postal_code = \core::$app->get_request()->get("postal_code-input", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $options);
						$suburb->save();
					}

					$address->add_ref_suburb = $suburb->sub_id;
					break;
				case "add_ref_town":
					$find_arr = [];
					$find_arr["create"] = true;
					$find_arr[".tow_name"] = $value;
					if(!$address->is_empty("add_ref_province")) $find_arr[".tow_ref_province"] = $address->add_ref_province;
					if(!$address->is_empty("add_ref_country")) $find_arr[".tow_ref_country"] = $address->add_ref_country;

					$town = \core::dbt("town")->find($find_arr);
					if($town->source != "database") $town->save();

					$address->add_ref_town = $town->tow_id;
					break;
				case "add_ref_province":
					$find_arr = [];
					$find_arr["create"] = true;
					$find_arr[".prv_name"] = $value;
					if(!$address->is_empty("add_ref_country")) $find_arr[".prv_ref_country"] = $address->add_ref_country;

					$province = \core::dbt("province")->find($find_arr);
					if($province->source != "database") $province->save();

					$address->add_ref_province = $province->prv_id;
					break;
				case "add_ref_country":
					$find_arr = [];
					$find_arr["create"] = true;
					$find_arr[".con_name"] = $value;

					$country = \core::dbt("country")->find($find_arr);
					if($country->source != "database") $country->save();

					$address->add_ref_country = $country->con_id;
					break;

				default:
					$address->{$field} = $value;
					break;
			}
			message(true);
		};

		$fn_parse_google_places_values("country-input", "add_ref_country", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		$fn_parse_google_places_values("administrative_area_level_1-input", "add_ref_province", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		$fn_parse_google_places_values("locality-input", "add_ref_town", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		$fn_parse_google_places_values("sublocality_level_2-input", "add_ref_suburb", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		$fn_parse_google_places_values("location-input", "add_street", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		$fn_parse_google_places_values("postal_code-input", "add_code", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		$fn_parse_google_places_values("apt-input", "add_streetnr", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		$fn_parse_google_places_values("add_gps_lat", "add_gps_lat", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		$fn_parse_google_places_values("add_gps_lng", "add_gps_lng", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);

		// return all the changes that were made
		return $address->get_changes();
	}
 	//--------------------------------------------------------------------------------
}