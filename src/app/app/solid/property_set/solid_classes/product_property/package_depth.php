<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\product_property;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class package_depth extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Package Depth";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PRODUCT_PROPERTY_PACKAGE_DEPTH";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.package:depth";
	}
	//--------------------------------------------------------------------------------
	public function allow_external_override($options = []) {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \com\data::TYPE_FLOAT;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $mixed
	 * @return mixed|string
	 */
	public function format($mixed, $options = []){
		return $this->parse($mixed)." cm";
	}
	//--------------------------------------------------------------------------------
}
