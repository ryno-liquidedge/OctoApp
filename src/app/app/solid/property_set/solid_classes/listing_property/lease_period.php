<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

define("LEASE_PERIOD" , [
    "1_month" => "1 Month",
    "2_month" => "2 Months",
    "3_month" => "3 Months",
    "4_month" => "4 Months",
    "5_month" => "5 Months",
    "6_month" => "6 Months",
    "7_month" => "7 Months",
    "8_month" => "8 Months",
    "9_month" => "9 Months",
    "10_month" => "10 Months",
    "11_month" => "11 Months",
    "12_month" => "1 Year",
    "24_month" => "2 Years",
    "36_month" => "3 Years",
    "48_month" => "4 Years",
    "60_month" => "5 Years",
    "72_month" => "6 Years",
    "negotiable" => "Negotiable",
]);

class lease_period extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Lease Period";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_LEASE_PERIOD";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:LEASE_PERIOD";
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
		return LEASE_PERIOD;
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
