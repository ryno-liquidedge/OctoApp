<?php

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class promotion extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "promotion";
    public $key = "prm_id";
    public $display = "prm_title";
    
    public $display_name = "promotion";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "prm_id"                    => array("id"                       , "null"    , DB_INT),
        "prm_title"                 => array("title"                    , ""        , DB_STRING),
        "prm_description"           => array("description"              , ""        , DB_TEXT),
        "prm_tag1"                  => array("tag 1"                    , ""        , DB_STRING),
        "prm_tag2"                  => array("tag 2"                    , ""        , DB_STRING),
        "prm_external_link_1"       => array("External Link 1"          , ""        , DB_STRING),
        "prm_external_link_label_1" => array("External Link label 1"    , ""        , DB_STRING),
        "prm_external_link_2"       => array("External Link 2"          , ""        , DB_STRING),
        "prm_external_link_label_2" => array("External Link label 2"    , ""        , DB_STRING),
        "prm_type"                  => array("type"                     , 0         , DB_ENUM),
        "prm_date_start"            => array("start date"               , "null"    , DB_DATETIME),
        "prm_date_end"              => array("end date"                 , "null"    , DB_DATETIME),
        "prm_is_published"          => array("published"                , 0         , DB_BOOL),
        "prm_discount_percentage"   => array("percentage discount"      , 0.00000   , DB_DECIMAL),
        "prm_discount_fixed"        => array("fixed discount"           , 0.00000   , DB_DECIMAL),
        "prm_ref_person_created"    => array("created by"               , "null"    , DB_REFERENCE, "person"),
        "prm_date_created"          => array("date created"             , "null"    , DB_DATETIME),
        "prm_date_modified"         => array("date modified"            , "null"    , DB_DATETIME),
        "prm_order"					=> array("order"					, 0         , DB_INT),
        "prm_coupon_code"           => array("coupon code"              , ""        , DB_STRING),
    );
    
    //--------------------------------------------------------------------------------
    // enums
    //--------------------------------------------------------------------------------
    public $prm_type = [
        0 => "-- Not Selected --",
        1 => "Standard Promotion",
        2 => "Homepage Popup Promotion",
    ];
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
    public function on_insert(&$obj) {
        parent::on_insert($obj);
        $obj->prm_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
        $obj->prm_date_modified = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
        $obj->prm_ref_person_ceated = \com\user::$active_id;

        if($obj->prm_type == 3) $obj->prm_title = "Custom Discount";
    }
    //--------------------------------------------------------------------------------
    public function on_update(&$obj, &$current_obj) {
        parent::on_update($obj, $current_obj);
        $obj->prm_date_modified = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();

        if($obj->prm_type == 3) $obj->prm_title = "Custom Discount";
    }
    //--------------------------------------------------------------------------------
    public function on_delete(&$obj) {
        $product_promotion_arr = $obj->get_product_promotion_arr();
        foreach ($product_promotion_arr as $product_promotion) {
            $product_promotion->delete();
        }

        $promotion_file_item_arr = $obj->get_promotion_file_item_arr();
        foreach ($promotion_file_item_arr as $promotion_file_item) {
            $promotion_file_item->delete();
        }
    }
    //--------------------------------------------------------------------------------

    /**
     * @param $promotion
     * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_product_promotion[]
     */
    public function get_product_promotion_arr($promotion) {
        return \core::dbt("product_promotion")->get_fromdb("ppm_ref_promotion = {$promotion->id}", ["multiple" => true]);
    }
    //--------------------------------------------------------------------------------

    /**
     * @param $promotion
     * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_promotion_file_item[]
     */
    public function get_promotion_file_item_arr($promotion) {
        return \core::dbt("promotion_file_item")->get_fromdb("prf_ref_promotion = {$promotion->id}", ["multiple" => true]);
    }
    //--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return array|false|mixed|db_promotion[]
     */
    public static function get_active_promotion_arr($options = []) {

        $options = array_merge([
            ".prm_is_published" => 1,
            "orderby" => "prm_order ASC",
        ], $options);

        $date = \app\date::strtodatetime();

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("promotion.*");
		$sql->from("promotion");
		$sql->and_where("(prm_date_start IS NULL OR prm_date_start < ".dbvalue($date).")");
		$sql->and_where("(prm_date_end IS NULL OR prm_date_end > ".dbvalue($date).")");
		$sql->extract_options($options);

        return \core::dbt("promotion")->get_fromsql($sql->build(), ["multiple" => true]);
    }
    //--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return array|false|mixed|db_promotion[]
     */
    public static function has_promotions($options = []) {

        $options = array_merge([
            ".prm_is_published" => 1,
            "orderby" => "prm_order ASC",
        ], $options);

        $date = \app\date::strtodatetime();

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("prm_id");
		$sql->from("promotion");
		$sql->and_where("(prm_date_start IS NULL OR prm_date_start < ".dbvalue($date).")");
		$sql->and_where("(prm_date_end IS NULL OR prm_date_end > ".dbvalue($date).")");
		$sql->extract_options($options);

        return (bool) \core::db()->selectsingle($sql->build());
    }
    //--------------------------------------------------------------------------------
}