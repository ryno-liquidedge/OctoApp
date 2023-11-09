<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\person_property;

/**
 * @package app\property_set\solid_classes\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class social_youtube extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Social Youtube";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PERSON_PROPERTY_SOCIAL_YOUTUBE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.person.property.social:YOUTUBE";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
