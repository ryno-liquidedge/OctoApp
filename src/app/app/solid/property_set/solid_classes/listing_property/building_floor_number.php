<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class building_floor_number extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Building Floor Number";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_BUILDING_FLOOR_NUMBER";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:FEATURE:FLOOR";
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
			"1" => "1st Floor",
			"2" => "2nd Floor",
			"3" => "3rd Floor",
			"4" => "4th Floor",
			"5" => "5th Floor",
			"6" => "6th Floor",
			"7" => "7th Floor",
			"8" => "8th Floor",
			"9" => "9th Floor",
			"10" => "10th Floor",
			"11" => "11th Floor",
			"12" => "12th Floor",
			"13" => "13th Floor",
			"14" => "14th Floor",
			"15" => "15th Floor",
			"16" => "16th Floor",
			"17" => "17th Floor",
			"18" => "18th Floor",
			"19" => "19th Floor",
			"20" => "20th Floor",
			"21" => "21st Floor",
			"22" => "22nd Floor",
			"23" => "23rd Floor",
			"24" => "24th Floor",
			"25" => "25th Floor",
			"26" => "26th Floor",
			"27" => "27th Floor",
			"28" => "28th Floor",
			"29" => "29th Floor",
			"30" => "30th Floor",
			"31" => "31st Floor",
			"32" => "32nd Floor",
			"33" => "33rd Floor",
			"34" => "34th Floor",
			"35" => "35th Floor",
			"36" => "36th Floor",
			"37" => "37th Floor",
			"38" => "38th Floor",
			"39" => "39th Floor",
			"40" => "40th Floor",
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
