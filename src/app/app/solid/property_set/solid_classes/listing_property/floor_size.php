<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class floor_size extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Floor Size m²";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_FLOOR_SIZE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:FLOOR_SIZE";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_FLOAT;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $mixed
	 * @return mixed
	 */
	public function format($mixed, $options = []){
		return $this->parse($mixed)." m²";
	}
    //--------------------------------------------------------------------------------
}
