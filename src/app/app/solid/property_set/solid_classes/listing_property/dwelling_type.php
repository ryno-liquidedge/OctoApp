<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

define("DWELLING_TYPE_ARRAY" , [
    "apartment" => "Apartment",
	"free_standing_house" => "Free Standing House",
	"factory" => "Factory",
	"industrial" => "Industrial",
	"retail" => "Retail",
	"residential" => "Residential",
	"vacant_land" => "Vacant Land",
	"warehouse" => "Warehouse",
	"office" => "Office",
	"shopping_centre" => "Shopping Centre",
	"townhouse" => "Townhouse",
	"freehold" => "Freehold",
	"duplex" => "Duplex",
	"house" => "House",
]);

class dwelling_type extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Listing Type";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_DWELLING_TYPE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.property:dwelling_type";
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
		return DWELLING_TYPE_ARRAY;
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
