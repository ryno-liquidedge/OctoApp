<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\person_property;

/**
 * @package app\property_set\solid_classes\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class map_str extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Map String";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PERSON_PROPERTY_MAP_STR";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.person.property:map:str";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_HTML;
	}
	//--------------------------------------------------------------------------------
    public function parse($mixed, $options = []) {
        return \LiquidedgeApp\Octoapp\app\app\data\data::parse($mixed, $this->get_data_type(), [
            "allow_tag_arr" => ["a", "iframe"]
        ]);
    }
    //--------------------------------------------------------------------------------
}
