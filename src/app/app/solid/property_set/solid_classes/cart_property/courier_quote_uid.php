<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\cart_property;

/**
 * @package app\property_set\solid_classes\person_owner
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class courier_quote_uid extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Courier Quote UID";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "CART_PROPERTY_COURIER_QUOTE_UID";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.order.courier.quote:UID";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
