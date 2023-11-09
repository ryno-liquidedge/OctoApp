<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\cart_property;

/**
 * @package app\property_set\solid_classes\person_owner
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class order_reference extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Order Reference";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "CART_PROPERTY_ORDER_REFERENCE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.order.client:orderReference";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
