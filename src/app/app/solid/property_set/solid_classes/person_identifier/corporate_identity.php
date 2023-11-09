<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\person_identifier;

/**
 * @package app\property_set\solid_classes\person_owner
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class corporate_identity extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Corporate Identity";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PERSON_IDENTIFIER_CORPORATE_IDENTITY";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "urn:gs1:gdd:cl:PartyRoleCode:CORPORATE_IDENTITY";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
