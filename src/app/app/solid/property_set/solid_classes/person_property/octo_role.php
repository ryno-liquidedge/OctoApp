<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\person_property;

/**
 * @package app\property_set\solid_classes\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class octo_role extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Octo Role";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PERSON_PROPERTY_OCTO_ROLE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.person.property:OCTO_ROLE";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $mixed
	 * @return mixed|string
	 */
	public function format($mixed, $options = []){

	    if(!$mixed) return $this->get_display_name();

	    $mixed_parts = explode(":", $mixed);

	    $label = end($mixed_parts);

	    return \LiquidedgeApp\Octoapp\app\app\solid\str::propercase($label);
	}
	//--------------------------------------------------------------------------------
}
