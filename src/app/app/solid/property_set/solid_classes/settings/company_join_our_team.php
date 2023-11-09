<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class company_join_our_team extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Join Our Team";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_COMPANY_JOIN_OUR_TEAM";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:COMPANY_JOIN_OUR_TEAM";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_HTML;
	}
    //--------------------------------------------------------------------------------
	public function parse($mixed, $options = []) {

	    $options = array_merge([
	        "allow_tag_arr" => ["a", "iframe"],
	    ], $options);

        return parent::parse($mixed, $options);
    }
    //--------------------------------------------------------------------------------
    public function get_value($options = []) {
        $options = array_merge([
		    "html_decode" => true
		], $options);

		if(!$options["html_decode"]) return parent::get_value($options);

        return html_entity_decode(parent::get_value($options));
    }
    //--------------------------------------------------------------------------------
}
