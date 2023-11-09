<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class retail_price_per_square extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Retail Price per m<sup>2</sup>";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_RETAIL_PRICE_PER_SQUARE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.property:RETAIL_PRICE_PER_SQUARE";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_CURRENCY;
	}
	//--------------------------------------------------------------------------------
	public function format($mixed, $options = []){
		return \LiquidedgeApp\Octoapp\app\app\num\num::currency($this->parse($mixed));
	}
    //--------------------------------------------------------------------------------
}
