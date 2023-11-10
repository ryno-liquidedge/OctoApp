<?php

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class product_promotion extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "product_promotion";
    public $key = "ppm_id";
    public $display = "ppm_ref_product";
    
    public $display_name = "product_promotion";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "ppm_id"                    => array("id"                   , "null"    , DB_INT),
        "ppm_ref_product"           => array("product"              , "null"    , DB_REFERENCE, "product"),
        "ppm_ref_promotion"         => array("promotion"            , "null"    , DB_REFERENCE, "promotion"),
        "ppm_ref_file_item"         => array("file_item"            , "null"    , DB_REFERENCE, "file_item"),
        "ppm_is_featured"           => array("is featured"          , 0         , DB_BOOL),
        "ppm_discount_percentage"   => array("discount percentage"  , 0.00000   , DB_CURRENCY),
        "ppm_discount_value"        => array("discount price"       , 0.00000   , DB_CURRENCY),
    );
    //--------------------------------------------------------------------------------
    // events
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check("system_users");
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check("system_users");
    }
    //--------------------------------------------------------------------------------
    public function get_product($product_promotion, $field = false) {
        $product = false;
        if (!$product_promotion->is_empty("ppm_ref_product")) {
            $product = $product_promotion->product;
        }

        return $product && $field ? $product->{$field} : $product;
    }
    //--------------------------------------------------------------------------------
    public function format_discount($product_promotion) {

        $promotion = $product_promotion->promotion;

        if($promotion->prm_type == 3) return floatval($product_promotion->ppm_discount_percentage)." %";

        if(floatval($promotion->prm_discount_fixed) != 0) return \com\num::currency($promotion->prm_discount_fixed);
        return floatval($promotion->prm_discount_percentage)." %";
    }
    //--------------------------------------------------------------------------------
    public function get_tags($product_promotion) {

        $return_arr = [];
        $promotion = $product_promotion->promotion;

        if($promotion->prm_type == 3){
            $return_arr[] = floatval($product_promotion->ppm_discount_percentage)."% Discount";
        }else{
            $return_arr[] = $promotion->prm_tag1;
            $return_arr[] = $promotion->prm_tag2;
        }

        return array_filter($return_arr);
    }
    //--------------------------------------------------------------------------------
    public static function get_all_promo_products_arr($sql_where = false, $options = []) {
        $options_arr = array_merge([
            "ppm_is_featured" => false,
            "prm_is_published" => true,
            "pro_is_enabled" => true,
            "limit" => false,
        ], $options);
        
        
        $query = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $query->distinct();
        $query->select("product.*");
        $query->from("product");
        $query->from("LEFT JOIN product_promotion ON(ppm_ref_product = pro_id)");
        $query->from("LEFT JOIN promotion ON(ppm_ref_promotion = prm_id)");

        if($options_arr['pro_is_enabled'] !== false) $query->from("
            LEFT JOIN ( SELECT * FROM product_property AS a JOIN (
                SELECT MAX(pdp_is_custom) AS max_custom, pdp_key AS inner_key, pdp_ref_product AS inner_ref_product 
                 FROM product_property 
                 WHERE (pdp_key = '".PRODUCT_PROPERTY_PRO_IS_INACTIVE."') 
                 GROUP BY pdp_ref_product, pdp_key
             ) AS b ON (a.pdp_key = b.inner_key AND a.pdp_is_custom = b.max_custom AND a.pdp_ref_product = b.inner_ref_product) ) AS is_disabled_table ON (pro_id = is_disabled_table.pdp_ref_product) 
        ");

		if($sql_where) $query->where("AND", $sql_where);
        
        if($options_arr['ppm_is_featured'] !== false)$query->where("AND", "ppm_is_featured = {$options_arr['ppm_is_featured']}");
        if($options_arr['prm_is_published'] !== false)$query->where("AND", "prm_is_published = {$options_arr['prm_is_published']}");
        if($options_arr['limit'] !== false) $query->top($options_arr['limit']);
        if($options_arr['pro_is_enabled'] !== false) $query->and_where("is_disabled_table.pdp_value IS NULL");

        return \core::dbt("product")->get_fromsql($query->build(), ["multiple" => true]);
    }
    //--------------------------------------------------------------------------------
    public static function get_all_promo_products_list($sql_where = false, $options = []) {
        $options_arr = array_merge([
            "pro_is_featured" => false,
            "prm_is_published" => true,
            "pro_is_enabled" => true,
            "limit" => false,
        ], $options);
        
        
        $query = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$query->select("pro_id, pro_name");
		$query->from("
			product
			LEFT JOIN product_promotion ON(ppm_ref_product = pro_id) 
            LEFT JOIN promotion ON(ppm_ref_promotion = prm_id) 
		");
		
		if($sql_where) $query->where("AND", $sql_where);
        
        if($options_arr['pro_is_featured'] !== false){
            $query->where("AND", "pro_is_featured = {$options_arr['pro_is_featured']}");
        }
        if($options_arr['prm_is_published'] !== false){
            $query->where("AND", "prm_is_published = {$options_arr['prm_is_published']}");
        }
        if($options_arr['limit'] !== false){
            $query->top($options_arr['limit']);
        }
        if($options_arr['pro_is_enabled'] !== false){
            $query->where_bool_product_property(PRODUCT_PROPERTY_PRO_IS_INACTIVE, 0);
        }
		
        return \com\db::selectlist($query->build(), "pro_id", "pro_name");
    }
    //--------------------------------------------------------------------------------
    public static function has_promo_products($sql_where = false, $options = []) {
        $options_arr = array_merge([
            "pro_is_featured" => false,
            "prm_is_published" => true,
            "pro_is_enabled" => true,
            "limit" => false,
        ], $options);
        
        
        $query = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$query->select("COUNT(pro_id)");
		$query->from("
			product
			LEFT JOIN product_promotion ON(ppm_ref_product = pro_id) 
            LEFT JOIN promotion ON(ppm_ref_promotion = prm_id) 
		");
		
		if($sql_where) $query->where("AND", $sql_where);
        
        if($options_arr['pro_is_featured'] !== false){
            $query->where("AND", "pro_is_featured = {$options_arr['pro_is_featured']}");
        }
        if($options_arr['prm_is_published'] !== false){
            $query->where("AND", "prm_is_published = {$options_arr['prm_is_published']}");
        }
        if($options_arr['limit'] !== false){
            $query->top($options_arr['limit']);
        }
        if($options_arr['pro_is_enabled'] !== false){
            $query->where_bool_product_property(PRODUCT_PROPERTY_PRO_IS_INACTIVE, 0);
        }
		
        return \com\db::selectsingle($query->build());
    }
    //--------------------------------------------------------------------------------
}