<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class building_storey_type extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Building Storey Type";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_BUILDING_STOREY_TYPE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:FEATURE:BUILDING_STORY";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ENUM;
	}
	//--------------------------------------------------------------------------------
	public function get_data_arr(): array {
		return [
			"single_storey" => "Single Storey",
			"double_storey" => "Double Storey",
			"triple_storey" => "Triple Storey",
			"multi_storey" => "Multiple Storey",
		];
	}
	//--------------------------------------------------------------------------------
	public function parse($mixed, $options = []) {
		return \LiquidedgeApp\Octoapp\app\app\data\data::parse($mixed, \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $options);
	}
    //--------------------------------------------------------------------------------
	public function get_default() {
		return "";
	}
	//--------------------------------------------------------------------------------
}
