<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\product_property;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class pro_long_description extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Long Description";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PRODUCT_PROPERTY_PRO_LONG_DESCRIPTION";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:productDescription:LONG";
	}
	//--------------------------------------------------------------------------------
	public function allow_external_override($options = []) {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \com\data::TYPE_TEXT;
	}
	//--------------------------------------------------------------------------------
}
