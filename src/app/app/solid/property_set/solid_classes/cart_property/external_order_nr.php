<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\cart_property;

/**
 * @package app\property_set\solid_classes\person_owner
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class external_order_nr extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "External Order Nr";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "CART_PROPERTY_EXTERNAL_ORDER_NUMBER";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.order.misc:external_order_number";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
