<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\person_property;

/**
 * @package app\property_set\solid_classes\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class biography extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Biography";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PERSON_PROPERTY_BIOGRAPHY";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.person.property:BIOGRAPHY";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_TEXT;
	}
	//--------------------------------------------------------------------------------
}
