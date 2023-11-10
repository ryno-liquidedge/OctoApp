<?php

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class product extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "product";
    public $key = "pro_id";
    public $display = "pro_name";

    public $display_name = "product";
    public $property_table = "product_property";
    public $slug = "pro_slug";

    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "pro_id"                            => array("database id"                  , "null"    , DB_INT),
		"pro_name"                          => array("name"                         , ""		, DB_STRING),
		"pro_type" 					        => array("type"					        , 0			, DB_ENUM),
		"pro_description" 					=> array("description"					, ""		, DB_TEXT),
		"pro_long_description" 				=> array("Detailed Description"			, ""		, DB_TEXT),
		"pro_meta_description" 				=> array("meta Description"				, ""		, DB_TEXT),
		"pro_alternate_name" 				=> array("alternate Name"				, ""		, DB_STRING),
		"pro_key"                           => array("Product Code"                 , ""		, DB_STRING),
		"pro_disambiguating_description" 	=> array("disambiguating description"	, ""    	, DB_TEXT),
        "pro_remote_id"                     => array("remote id"                    , 0			, DB_INT),
        "pro_is_deleted"                    => array("is deleted"                   , 0         , DB_BOOL),
        "pro_is_new"                        => array("is new"                       , 0         , DB_BOOL),
        "pro_is_secure"                     => array("is secure"                    , 0         , DB_BOOL),
        "pro_is_published"                  => array("is published"                 , 0         , DB_BOOL),
        "pro_is_featured"                   => array("is featured"                  , 0         , DB_BOOL),
        "pro_date_modified"                 => array("date modified"                , "null"    , DB_DATE),
        "pro_date_created"                  => array("date created"                 , "null"    , DB_DATE),
        "pro_date_last_sync"                => array("date last sync"               , "null"    , DB_DATE),
        "pro_date_last_force_sync"      	=> array("date last force sync"       	, "null"    , DB_DATE),
        "pro_sync_status" 				    => array("type"					        , 0			, DB_ENUM),
        "pro_findstring"                    => array("findstring"                   , ""        , DB_TEXT),
        "pro_ref_product"					=> array("product"						, "null"	, DB_REFERENCE, "product"),
        "pro_ref_address"					=> array("address"						, "null"	, DB_REFERENCE, "address"),
        "pro_ref_person_created"		    => array("person created"			    , "null"	, DB_REFERENCE, "person"),
        "pro_slug"                          => array("seo_name"                     , ""        , DB_STRING),
        "pro_source"                      	=> array("source"                     	, 0			, DB_ENUM),
        "pro_price"                      	=> array("price"                     	, 0			, DB_DECIMAL),
    );
    //--------------------------------------------------------------------------------
    public $pro_is_enabled = [
        0 => "Unpublished",
        1 => "Published",
    ];
    //--------------------------------------------------------------------------------
    public $pro_source = [
        0 => "Octo",
        1 => "Shop",
    ];
    //--------------------------------------------------------------------------------
    public $pro_sync_status = [
        0 => "Not Processing",
        1 => "Processing",
        2 => "Pending Push",
    ];
    //--------------------------------------------------------------------------------
    public $pro_is_secure = [
        0 => "No",
        1 => "Yes",
    ];
    //--------------------------------------------------------------------------------
    public $pro_is_featured = [
        0 => "No",
        1 => "Yes",
    ];
    //--------------------------------------------------------------------------------
    public $pro_type = [

        0 => "N/A",

        //general
        1 => "General Goods",

    ];
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
    public function get_fromdb($mixed, $options = array()) {

        $obj = parent::get_fromdb($mixed, $options);
        $this->decode_obj($obj, $options);

        return $obj;
    }
    //--------------------------------------------------------------------------------
    public function get_fromdefault() {
        $obj = parent::get_fromdefault();
        $obj->pro_key = $this->generate_product_key();
        return $obj;
    }

    //--------------------------------------------------------------------------------
    public function on_insert(&$obj) {
        $obj->pro_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
        $obj->pro_date_modified = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
    }
    //--------------------------------------------------------------------------------
    public function on_update(&$obj, &$current_obj) {

        $obj->build_findstring();

		if($obj->is_empty("pro_slug"))
		    $obj->pro_slug = $obj->build_slug();

		if($obj->pro_slug != $obj->get_prop(PRODUCT_PROPERTY_SEO_CODE)) $obj->save_property(PRODUCT_PROPERTY_SEO_CODE, $obj->pro_slug);
		if($obj->pro_is_featured != $current_obj->pro_is_featured) $obj->save_property(PRODUCT_PROPERTY_IS_FEATURED, $obj->pro_is_featured);

		if(!$obj->get_changes()) return;

		if($obj->get_changes()){
            $obj->pro_date_modified = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
        }
    }
    //--------------------------------------------------------------------------------
    public function on_save(&$obj) {
        $this->encode_obj($obj);
    }
    //--------------------------------------------------------------------------------
    public function is_deleted($obj) {
		return $obj->pro_is_deleted == 1;
    }
    //--------------------------------------------------------------------------------
    public function is_sync_pending($obj) {
		return $obj->pro_sync_status == PRO_SYNC_STATUS_PENDING_PUSH;
    }
    //--------------------------------------------------------------------------------
    public function is_sync_processing($obj) {
		return $obj->pro_sync_status == PRO_SYNC_STATUS_PROCESSING;
    }
    //--------------------------------------------------------------------------------
    public function set_sync_pending($obj) {
		$obj->set_sync_status(PRO_SYNC_STATUS_PENDING_PUSH);
    }
    //--------------------------------------------------------------------------------
    public function set_sync_processing($obj) {
		$obj->set_sync_status(PRO_SYNC_STATUS_PROCESSING);
    }
    //--------------------------------------------------------------------------------
    public function set_sync_complete($obj) {
		$obj->set_sync_status(PRO_SYNC_STATUS_NOT_PROCESSING);
    }
    //--------------------------------------------------------------------------------
    public static function generate_product_key($options = []) {
        $options = array_merge([
            "index" => false,
            "prefix" => "PRODUCT",
        ], $options);

        if(!$options["index"]) $options["index"] = \core::dbt("product")->get_last_inserted_id()+1;

		$return[] = $options["prefix"];
		$return[] = $options["index"];
		$return[] = time();

		return implode("-", $return);
    }
    //--------------------------------------------------------------------------------
    public function get_description($product, $options = []) {

        return $product->pro_description;
    }
    //--------------------------------------------------------------------------------
    public function get_detailed_description($product, $options = []) {

        return $product->pro_long_description;
    }
    //--------------------------------------------------------------------------------
    public function pro_long_description($product, $options = []) {

        return $product->pro_long_description;
    }
    //--------------------------------------------------------------------------------
    public function get_total_sales($product, $options = []) {
		return 0;
    }
    //--------------------------------------------------------------------------------
    public function build_findstring($product) {

        $pro_findstring = [];
        $pro_findstring[] = $product->get_name();
        $pro_findstring[] = $product->pro_key;

        $pro_findstring = array_filter($pro_findstring);
        $str = implode(",", $pro_findstring);

        return $product->pro_findstring = str_replace(" ", "", strlen($str) > 240 ? substr($str, 0, 240) : $str);
    }
    //--------------------------------------------------------------------------------
    public function is_in_stock($product) {
        return (bool) false;
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $product
	 * @param array $options
	 * @return int
	 */
    public function get_stock_total($product, $options = []) {

    	if(property_exists($product, "total_stock")) return $product->total_stock;

		$product_property_arr = db_product_property::get_product_property($product, PRODUCT_PROPERTY_STOCK, ["multiple" => true]);

		$stock = 0;
		foreach ($product_property_arr as $product_property){
			$pdp_value = \app\app::parse_product_property($product_property->pdp_value, PRODUCT_PROPERTY_STOCK);
			$stock += ($pdp_value > 0 ? $pdp_value : 0);
		}

		return $product->total_stock = $stock;

	}
    //--------------------------------------------------------------------------------
    public function get_stock($product, $options = []) {
        $options = array_merge([
			"default" => 0,
        ], $options);

        return $options["default"];
    }
    //--------------------------------------------------------------------------------
    public function get_lead_time($product, $options = []) {
        $options_arr = array_merge([
			"format" => false,
			"default" => false,
        ], $options);
        
		return $options["default"];
    }
	//--------------------------------------------------------------------------------

	public function api_update($product, $options = []) {

		$options = array_merge([
		    "debug" => false
		], $options);

		$octoapi = \app\api\octoapi\api::make()->get_instance();
		$octoapi->set_debug($options["debug"]);
		$response = $octoapi->product()->get()->get_product_data($product->pro_remote_id);

		$product_data = $response->get_response_data_first();

		if(!isset($product_data["pro_id"])) $product_data["pro_id"] = $product->pro_remote_id;
        if(!isset($product_data["id"])) $product_data["id"] = $product->pro_remote_id;

        $product_parser = \app\api\octoapi\parser\product::make();
        $product_parser->set_data_arr($product_data);
        $product_parser->run();

	}
	//--------------------------------------------------------------------------------
    public function has_promo_price($product) {
        return $product->get_promo_price() > 0;
    }
	//--------------------------------------------------------------------------------
    public function get_promo_price($product) {

        if(!\com\release::is_active("!TUBES-17")) return 0;

        if(property_exists($product, "pro_price_promo") && (bool)$product->pro_price_promo){
            return $product->pro_price_promo;
        }else{
            $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
            $sql->select("ppm_discount_value");
            $sql->from("product_promotion");
            $sql->from("LEFT JOIN promotion ON (ppm_ref_promotion = prm_id)");
            $sql->and_where("prm_is_published = 1");
            $sql->and_where("ppm_ref_product = ".dbvalue($product->pro_id));
            $sql->or_where("prm_date_end IS NULL");
            $sql->or_where("prm_date_end > ".dbvalue(\app\date::strtodatetime()));
            $sql->orderby("prm_order ASC");

            return $product->pro_price_promo = \core::db()->selectsingle($sql->build());
        }
    }
	//--------------------------------------------------------------------------------
    public function is_pcr($product, $options = []) {
    	return $product->get_prop(PRODUCT_PROPERTY_UNIT_TYPE);
//    	return $product->get_prop(PRODUCT_PROPERTY_IS_PCR);
	}
	//--------------------------------------------------------------------------------
    public function get_price($product, $options = []) {

        $options = array_merge([
            "apply_promo_price" => true
        ], $options);

        if(\com\release::is_active("!TUBES-17")){
            //first see if the product has a special
            if($options["apply_promo_price"] && $product->has_promo_price()){
                return $product->get_promo_price();
            }
        }

        $value = $product->pro_price;

        $is_pcr = $product->is_pcr();
        if($is_pcr) return $value;

        //apply active user discount
        $discount = \app\session::get_session_var("client_discount", function()use($value){
            $discount = 0;
            if(\com\user::$active){
                $discount = \LiquidedgeApp\Octoapp\app\app\data\data::parse_float(\com\user::$active->get_discount_value());
            }
            return $discount;
        });

        if($discount > 0){
            $value = $value - ($value * ($discount/100));
        }

        return $value;
    }
	//--------------------------------------------------------------------------------

    /**
     * @param $product
     * @return mixed
     */
    public function get_stock_list($product) {

        $solid = \app\solid::get_instance(PRODUCT_PROPERTY_STOCK);

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("pdp_ref_person");
        $sql->select("pdp_value");
        $sql->from("product_property");
        $sql->and_where("pdp_ref_product = ".dbvalue($product->id));
        $sql->and_where("pdp_key = ".dbvalue(PRODUCT_PROPERTY_STOCK));

        $list = \core::db()->selectlist($sql->build(), "pdp_ref_person", "pdp_value");

        foreach ($list as $index => $value){
            $list[$index] = $solid->parse($value);
        }

        return $list;

    }
	//--------------------------------------------------------------------------------

	/**
	 * Saves the product data to the octo server
	 * @param $product
	 */
	public function push_to_octo($product, $options = []) {

		$options = array_merge([
		    "debug" => false,
		], $options);

	}
    //--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return array|false|mixed|db_product[]
     */
    public static function get_featured_products_arr($options = []) {
        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("product.*");
        $sql->from("product");
        $sql->and_where("pro_is_featured = 1");
        $sql->and_where("pro_is_published = 1");
        $sql->and_where("pro_is_deleted = 0");
        $sql->orderby("pro_date_modified DESC");
        $sql->limit(5);
        $sql->extract_options($options);

        return \core::dbt("product")->get_fromsql($sql->build(), ["multiple" => true]);
    }
    //--------------------------------------------------------------------------------
    public function get_pending_order_qty($product) {
        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("COUNT(cti_qty)");
        $sql->from("cart_item");
        $sql->from("LEFT JOIN cart ON (cti_ref_cart = crt_id)");
        $sql->and_where("cti_ref_product = ".dbvalue($product->id));
        $sql->and_where(\core::db()->getsql_in([CRT_STATUS_COMPLETED, CRT_STATUS_PAYMENT_RECEIVED, CRT_STATUS_CANCELLED], "crt_status", false, true));

        return \core::db()->selectsingle($sql->build());
    }
    //--------------------------------------------------------------------------------
    /**
     * @param $product db_product
     * @param array $options
     * @return db_product_brand|false
     */
    public function get_product_brand($product, $options = []) {

        $options = array_merge([
            "field" => false
        ], $options);

        if(property_exists($product, "product_brand")) return $product->product_brand;

        $product_brand = $product->product_brand = \core::dbt("product_brand")->find([".pbr_ref_product" => $product->id]);

        if($options["field"]){
            if($product_brand) return $product_brand->{$options["field"]};
            else return false;
        }

        return $product_brand;
    }
    //--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return db_brand|false
     */
    public function get_brand($product, $options = []) {

        $options = array_merge([
            "field" => false
        ], $options);

        $product_brand = $product->get_product_brand();

        if($options["field"]){
            if($product_brand && $product_brand->brand) return $product_brand->brand->{$options["field"]};
            else return false;
        }

        if($product_brand && $product_brand->brand) return $product_brand->brand;
    }
    //--------------------------------------------------------------------------------
}
