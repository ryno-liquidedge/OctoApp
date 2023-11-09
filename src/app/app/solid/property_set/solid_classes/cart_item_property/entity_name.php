<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\cart_item_property;

/**
 * @package app\property_set\solid_classes\person_owner
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class entity_name extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Entity Name";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "CART_ITEM_PROPERTY_ENTITY_NAME";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.order.item.entity:name";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
