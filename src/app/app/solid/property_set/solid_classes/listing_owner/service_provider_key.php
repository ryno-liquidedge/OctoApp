<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_owner;

/**
 * @package app\property_set\solid_classes\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class service_provider_key extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Service Provider Key";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_OWNER_SERVICE_PROVIDER_KEY";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "urn:gs1:gdd:cl:AdditionalServiceRelationIdentificationTypeCode:SERVICE_PROVIDER_ASSIGNED";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
