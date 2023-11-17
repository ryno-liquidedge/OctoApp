<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class advertisement extends \com\db\table {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "advertisement";
    public $key = "adv_id";
    public $display = "adv_title";

    public $display_name = "advertisement";
    public $slug = "adv_slug";
    public $string = "adv_slug";

    public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        "adv_id"		        => array ("database id"		, "null"    , DB_INT),
        "adv_title"		        => array ("title"			, ""        , DB_STRING),
        "adv_description"		=> array ("description"		, ""        , DB_TEXT),
        "adv_url"		    	=> array ("URL"				, ""        , DB_STRING),
        "adv_is_active" 		=> array("is published"		, 0		    , DB_BOOL),
        "adv_status"            => array ("status"	        , 0	        , DB_ENUM),
        "adv_location"          => array ("ads location"	, 0	        , DB_ENUM),
        "adv_upload_date"       => array ("uploaded date"	, "null"    , DB_DATE),
        "adv_ref_person"		=> array ("person"			, "null"    , DB_REFERENCE, "person"),
        "adv_order"		    	=> array("order"			, 0	    	, DB_INT),
        "adv_slug"		    	=> array ("slug"			, ""        , DB_STRING),
    );
    //--------------------------------------------------------------------------------
    public $adv_location = [
        null => "--Not Selected--",
        ADV_SEARCH_BAR => "Search bar (250 x 500)",
        ADV_SEARCH_RESULTS => "Search results (850 x 200)",
        ADV_PROPERTY_PAGE => "Property page (550 x 220)",
    ];
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }

    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
    //--------------------------------------------------------------------------------
    public function on_insert(&$obj) {
		$obj->adv_slug = $obj->build_slug();
		$obj->adv_ref_person = \com\user::$active_id;
		$obj->adv_upload_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $advertisement
	 * @param array $options
	 * @return array|bool|mixed|db_advertisement_file_item[]
	 */
    public function get_advertisement_file_item_arr($advertisement, $options = []){

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("advertisement_file_item.*");
        $sql->from("advertisement_file_item");
        $sql->and_where("afi_ref_advertisement = {$advertisement->id}");
        $sql->extract_options($options);

        return \core::dbt('advertisement_file_item')->get_fromsql($sql->build(), ["multiple" => true]);
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $advertisement
	 * @param array $options
	 * @return array|bool|mixed|db_advertisement_file_item
	 */
    public function get_advertisement_file_item($advertisement, $options = []){

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("advertisement_file_item.*");
        $sql->from("advertisement_file_item");
        $sql->and_where("afi_ref_advertisement = {$advertisement->id}");
        $sql->extract_options($options);

        return \core::dbt('advertisement_file_item')->get_fromsql($sql->build());
    }
    //--------------------------------------------------------------------------------
	public static function has_advertisement($ad_location, $options = []) {
		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("adv_id");
		$sql->from("advertisement");
		$sql->and_where("adv_is_active = 1");
		$sql->and_where("adv_location = ".$ad_location);
		$sql->extract_options($options);

		return \core::db()->selectsingle($sql->build());
	}
    //--------------------------------------------------------------------------------
	/**
	 * @param $ad_location
	 * @param array $options
	 * @return array|false|mixed
	 */
	public static function get_data_advertisement_arr($ad_location, $options = []) {

		$options = array_merge([
		    "orderby" => false,
		    "sql_where" => false,
		    "limit" => 10,
		], $options);

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("advertisement.*");
		$sql->select("advertisement_file_item.*");

		$sql->from("advertisement");
		$sql->from('LEFT JOIN person ON (per_id = adv_ref_person)');
		$sql->from("LEFT JOIN advertisement_file_item ON (afi_ref_advertisement = adv_id AND afi_file_type = ".dbvalue(ASSET_MAIN_IMAGE).")");

		$sql->and_where("adv_is_active = 1");
		$sql->and_where("adv_location = ".$ad_location);
		$sql->extract_options($options);

		$result_arr = \core::db()->select($sql->build());

		array_walk($result_arr, function(&$advertisement){
			$advertisement["last_viewed"] = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
		});

		return $result_arr;
    }
    //--------------------------------------------------------------------------------
	public function get_asset_dimentions($advertisement) {
		switch ($advertisement->adv_location){
			case ADV_SEARCH_BAR: return ["width" => 250, "height" => 500];
			case ADV_SEARCH_RESULTS: return ["width" => 850, "height" => 200];
			case ADV_PROPERTY_PAGE: return ["width" => 550, "height" => 200];
		}

		return ["width" => 0, "height" => 0];
	}
    //--------------------------------------------------------------------------------
	public function set_advertisement_file_item($advertisement, $file_item, $file_item_original, $options = []) {

		$options = array_merge([
		    ".afi_ref_advertisement" => $advertisement->adv_id,
		    ".afi_file_type" => ASSET_MAIN_IMAGE,
		    "create" => true,
		], $options);

		$advertisement_file_item = \core::dbt("advertisement_file_item")->find($options);
		$advertisement_file_item->afi_ref_file_item = $file_item->id;
		$advertisement_file_item->afi_ref_file_item_original = $file_item_original->id;
		$advertisement_file_item->save();
	}
    //--------------------------------------------------------------------------------
}