<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\product_property;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class promotion_date_from extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Promotion Date From";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PRODUCT_PROPERTY_PROMOTION_DATE_FROM";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.promotion:DATE_FROM";
	}
	//--------------------------------------------------------------------------------
	public function allow_external_override($options = []) {
		return false;
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \com\data::TYPE_DATE;
	}
	//--------------------------------------------------------------------------------
}
