<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class listing extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "listing";
    public $key = "lis_id";
    public $display = "lis_name";

    public $display_name = "listing";
    public $slug = "lis_slug";

    public $property_table = "listing_property";

    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "lis_id"                    => array("id"                   , "null"	, DB_INT),
        "lis_name"				    => array("title"			    , ""		, DB_STRING),
        "lis_type" 				    => array("type"				    , 0			, DB_ENUM),
        "lis_sale_type" 		    => array("sale type"		    , 0			, DB_ENUM),
        "lis_status"           		=> array("status"          		, 0         , DB_ENUM),
        "lis_sync_status"  			=> array("sync status"     		, 0         , DB_ENUM),
        "lis_source"  				=> array("source"     			, 0         , DB_ENUM),

        "lis_description" 			=> array("description"		    , ""		, DB_TEXT),
		"lis_short_description" 	=> array("short description"    , "" 		, DB_TEXT),
		"lis_long_description" 		=> array("long description"	    , ""		, DB_TEXT),
		"lis_meta_description" 		=> array("meta description"	    , ""		, DB_TEXT),

        "lis_date_created"		    => array("date created"		    , "null"	, DB_DATETIME),
        "lis_date_modified"		    => array("date modified"	    , "null"	, DB_DATETIME),

        "lis_is_enabled"		    => array("is enabled"		    , 0			, DB_BOOL),
        "lis_is_featured"           => array("is featured"          , 0         , DB_BOOL),

        "lis_ref_person_created"    => array("person created"	    , "null"	, DB_REFERENCE, "person"),
        "lis_ref_person_tenant"    	=> array("person tenant"	    , "null"	, DB_REFERENCE, "person"),
        "lis_ref_listing"	        => array("listing"			    , "null"	, DB_REFERENCE, "listing"),
        "lis_price"	       			=> array("listed price"			, 0			, DB_DECIMAL),

        "lis_key"			        => array("key"			        , ""		, DB_STRING),
        "lis_key_alt"			  	=> array("key alt"		        , ""		, DB_STRING),
        "lis_slug"			        => array("slug"			        , ""		, DB_STRING),
        "lis_remote_id"             => array("remote id"            , 0	        , DB_INT),
        "lis_old_id"             	=> array("old db id"            , 0	        , DB_INT),
        "lis_date_last_api_push"	=> array("last api push"	    , "null"	, DB_DATETIME),
        "lis_date_last_api_update"	=> array("last api update"	    , "null"	, DB_DATETIME),
    );
    //--------------------------------------------------------------------------------
    public $lis_type = LISTING_TYPE_ARRAY;
    //--------------------------------------------------------------------------------
    public $lis_sync_status = SYNC_STATUS_ARRAY;
    //--------------------------------------------------------------------------------
    public $lis_status = [
		0 => "N/A",
		STATUS_ACTIVE => "Active",
		STATUS_ARCHIVED => "Archived",
		STATUS_PENDING => "Under Offer",
		STATUS_RENTED => "Rented",
		STATUS_SOLD => "Sold",
	];
    //--------------------------------------------------------------------------------
    public $lis_source = [
		0 => "N/A",
	];
    //--------------------------------------------------------------------------------
    public $lis_sale_type = [
        0 => "N/A",
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
		
		if($obj->is_empty("lis_date_created")) $obj->lis_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
		$obj->lis_ref_person_created = \com\user::$active_id;

		if(!$obj->is_empty("lis_key")){
			$obj->lis_key_alt = $obj->lis_key;
			$obj->lis_slug = $obj->lis_key;
		}else{
			$obj->lis_slug = $obj->build_slug();
		}
	}
	//--------------------------------------------------------------------------------
    public function on_update(&$obj, &$current_obj) {
		
		if($obj->is_empty("lis_date_created"))
		    $obj->lis_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();

		$obj->lis_slug = $obj->lis_key;
	}
	//--------------------------------------------------------------------------------
	public function on_save(&$obj) {
		$obj->lis_price = $obj->get_price();
	}
	//--------------------------------------------------------------------------------
    public function get_fromdefault() {
        $obj = parent::get_fromdefault();
        $obj->lis_key = self::generate_product_key();
        $obj->lis_key_alt = $obj->lis_key;
        $obj->lis_status = STATUS_ACTIVE;
        return $obj;
    }
    //--------------------------------------------------------------------------------
    public static function generate_product_key($options = []) {

		$return[] = strtoupper(substr(\core::$app->get_instance()->get_company(), 0, 3));
		$return[] = \core::dbt("listing")->get_next_id();
		$return[] = time();

		return implode("-", $return);
    }
	//--------------------------------------------------------------------------------

	/**
	 * checks to see if the email address is unique
	 * @param $listing db_listing
	 * @return bool
	 */
	public function is_unique($listing) {

		// params
		$listing = $this->splat($listing);

		// sql
		$sql = \com\db\sql\select::make();
		$sql->select("lis_id");
		$sql->from("listing");
		$sql->and_where("lis_key = ".dbvalue($listing->lis_key));

		// existing person
		if (!$listing->is_empty("lis_id")) $sql->and_where("lis_id <> '$listing->id'");

		// check for unique username
		return !(bool)\core::db()->selectsingle($sql->build());
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $listing
	 * @param array $options
	 * @return array|false|mixed|db_listing_asset
	 */
	public function get_main_listing_asset($listing, $options = []) {

		$options = array_merge([
		    "where" => \core::db()->getsql_in([ASSET_MAIN_IMAGE, ASSET_GALLERY_IMAGE], "lia_type"),
		    "orderby" => "lia_type ASC",
		], $options);

    	$listing_asset_arr = $listing->get_listing_asset_arr($options);

    	if($listing_asset_arr) return reset($listing_asset_arr);

	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $listing
	 * @param array $options
	 * @return array|false|mixed|db_listing_asset
	 */
	public function get_cover_listing_asset($listing, $options = []) {

		$options = array_merge([
		    ".lia_type" => ASSET_COVER_IMAGE,
		], $options);

    	$listing_asset_arr = $listing->get_listing_asset_arr($options);

    	if($listing_asset_arr) return reset($listing_asset_arr);

	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $listing
	 * @param array $options
	 * @return array|false|mixed|db_listing_asset[]
	 */
	public function get_listing_asset_arr($listing, $options = []) {

		$options = array_merge([
		    "orderby" => "lia_type ASC, lia_id ASC"
		], $options);

    	$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
    	$sql->select("listing_asset.*");
    	$sql->from("listing_asset");
    	$sql->and_where("lia_ref_listing = {$listing->lis_id}");
    	$sql->extract_options($options);
    	$sql->orderby($options["orderby"]);

    	return \core::dbt("listing_asset")->get_fromsql($sql->build(), ["multiple" => true]);

	}
    //--------------------------------------------------------------------------------
	/**
	 * @param $listing
	 * @param $person
	 * @param $lip_type
	 * @param array $options
	 * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|listing_person
	 */
	public function add_listing_person($listing, $person, $lip_type = LISTING_PERSON_AGENT, $options = []) {

		$listing_person = \core::dbt("listing_person")->find([
			".lpe_ref_listing" => $listing->id,
			".lpe_ref_person" => $person->id,
			".lpe_type" => $lip_type,
			"create" => true,
		]);

		if($listing_person->source != "database"){
			$listing_person->save();
		}

		return $listing_person;
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $listing
	 * @param array $options
	 * @return array|false|mixed|listing_person[]
	 */
	public function get_listing_person_list($listing, $options = []) {

		$options = array_merge([
		    "field1" => "lpe_id",
		    "field2" => "lpe_ref_person",
		    ".per_is_active" => 1
		], $options);

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("{$options["field1"]} AS field1");
		$sql->select("{$options["field2"]} AS field2");
		$sql->from("listing_person");
		$sql->from("LEFT JOIN person on (lpe_ref_person = per_id)");
		$sql->and_where("lpe_ref_listing = ".dbvalue($listing->id));
		$sql->extract_options($options);

		return \core::db()->selectlist($sql->build(), "field1", "field2");
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $listing
	 * @param array $options
	 * @return array|false|mixed|listing_person[]
	 */
	public function get_listing_person_arr($listing, $options = []) {

		$options = array_merge([
		    ".per_is_active" => 1
		], $options);

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("listing_person.*");
		$sql->from("listing_person");
		$sql->from("LEFT JOIN person ON (lpe_ref_person = per_id)");
		$sql->and_where("lpe_ref_listing = ".dbvalue($listing->id));
		$sql->extract_options($options);

		return \core::dbt("listing_person")->get_fromsql($sql->build(), ["multiple" => true]);
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $listing
	 * @param array $options
	 * @return array|false|mixed|listing_person[]
	 */
	public function get_listing_person($listing, $options = []) {

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("listing_person.*");
		$sql->from("listing_person");
		$sql->and_where("lpe_ref_listing = ".dbvalue($listing->id));
		$sql->extract_options($options);

		return \core::dbt("listing_person")->get_fromsql($sql->build());
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $listing
	 * @param array $options
	 * @return mixed|listing_person[]
	 */
	public function get_listing_person_landlord_arr($listing, $options = []) {

		$options = array_merge([
		    ".lpe_type" => LISTING_PERSON_LANDLORD,
		], $options);

		return $listing->get_listing_person_arr($options);
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $listing
	 * @param array $options
	 * @return mixed|listing_person[]
	 */
	public function get_listing_person_seller_arr($listing, $options = []) {

		$options = array_merge([
		    ".lpe_type" => LISTING_PERSON_SELLER,
		], $options);

		return $listing->get_listing_person_arr($options);
	}
    //--------------------------------------------------------------------------------

}