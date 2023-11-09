<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */


class status extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Status";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_STATUS";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:STATUS";
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
			0 => "N/A",
			STATUS_ACTIVE => "Active",
			STATUS_ARCHIVED => "Archived",
			STATUS_PENDING => "Under Offer",
			STATUS_RENTED => "Rented",
			STATUS_SOLD => "Sold",
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
