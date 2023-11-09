<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */


class feature_flooring_type extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Floor type";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_FEATURE_FLOORING_TYPE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:FEATURE:FLOORING";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ENUM;
	}
	//--------------------------------------------------------------------------------
	public function parse($mixed, $options = []) {
		return \LiquidedgeApp\Octoapp\app\app\data\data::parse($mixed, \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $options);
	}
	//--------------------------------------------------------------------------------
	public function get_data_arr(): array {
		return [
			"carpet" => "Carpet",
			"wood" => "Wood",
			"laminate" => "Laminate",
			"tiles" => "Tiles",
			"slate" => "Slate",
			"cement" => "Cement",
			"vinyl" => "Vinyl",
			"engineered Wood" => "Engineered Wood"
		];
	}
	//--------------------------------------------------------------------------------
	public function get_default() {
		return "";
	}
	//--------------------------------------------------------------------------------
	public function format($mixed, $options = []){
		$data_arr = $this->get_data_arr();
		return isset($data_arr[$mixed]) ? $data_arr[$mixed] : false;
	}
    //--------------------------------------------------------------------------------
}
