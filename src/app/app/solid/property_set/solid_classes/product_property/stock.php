<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\product_property;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class stock extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Stock";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PRODUCT_PROPERTY_STOCK";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "urn:gs1:gdd:cl:TransactionalReferenceTypeCode:GRN";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \com\data::TYPE_INT;
	}
	//--------------------------------------------------------------------------------
}
