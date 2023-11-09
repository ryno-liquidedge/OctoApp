<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

define("PROPERTY_TYPE_ARRAY" , [
	"apartment" => "Apartment",
	"apartment_block" => "Apartment Block",
	"business" => "Business",
	"business_park" => "Business Park",
	"chalet" => "Chalet",
	"church" => "Church",
	"double_room" => "Double Room",
	"duplex" => "Duplex",
	"equestrian" => "Equestrian",
	"factory" => "Factory",
	"farm" => "Farm",
	"flat" => "Flat",
	"four_room" => "Four Room",
	"freehold" => "Freehold",
	"garden_cottage" => "Garden Cottage",
	"gated_estate" => "Gated Estate",
	"golf_estate" => "Golf Estate",
	"guest_house" => "Guest House",
	"guesthouse" => "Guesthouse",
	"hotel" => "Hotel",
	"house" => "House",
	"industrial_park" => "Industrial Park",
	"industrial_yard" => "Industrial Yard",
	"maisonette" => "Maisonette",
	"medical_centre" => "Medical Centre",
	"medical_suite" => "Medical Suite",
	"mini_factory" => "Mini Factory",
	"mixed_use" => "Mixed use",
	"office" => "Office",
	"penthouse" => "Penthouse",
	"residential" => "Residential",
	"retail" => "Retail",
	"retirement" => "Retirement",
	"room" => "Room",
	"sectional_title" => "Sectional Title",
	"security_estate" => "Security Estate",
	"service_station" => "Service Station",
	"serviced_office" => "Serviced Office",
	"shopping_centre" => "Shopping Centre",
	"showroom" => "Showroom",
	"simplex" => "Simplex",
	"single_room" => "Single Room",
	"small_holding" => "Small Holding",
	"storage_units" => "Storage Units",
	"townhouse" => "Townhouse",
	"triple_room" => "Triple Room",
	"vacant_land" => "Vacant Land",
	"villa" => "Villa",
	"warehouse" => "Warehouse",
	"wildlife_estate" => "Wildlife Estate",
	"workshop" => "Workshop",
]);

class property_type extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Property Type";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_PROPERTY_TYPE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.property:property_type";
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
		return PROPERTY_TYPE_ARRAY;
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
