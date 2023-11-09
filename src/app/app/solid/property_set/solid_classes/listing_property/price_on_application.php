<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class price_on_application extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Price on Application";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_PRICE_ON_APPLICATION";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.property:PRICE_ON_APPLICATION";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_BOOL;
	}
    //--------------------------------------------------------------------------------
}
