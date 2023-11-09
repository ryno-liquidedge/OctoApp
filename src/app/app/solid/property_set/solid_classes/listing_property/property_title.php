<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

define("PROPERTY_TITLE_ARRAY" , [
    "freehold" 			=> "Freehold",
	"leasehold" 		=> "Leasehold",
	"sectional_title" 	=> "Sectional Title",
	"share_block" 		=> "Share Block",
]);

class property_title extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Property Title";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_PROPERTY_TITLE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.property:property_title";
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
		return PROPERTY_TITLE_ARRAY;
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
