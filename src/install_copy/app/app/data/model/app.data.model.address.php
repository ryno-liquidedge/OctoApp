<?php

namespace app\data\model;

/**
 * @package app\data\model
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class address extends \com\data\intf\model {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options) {
		// options
		$options = array_merge([
			"allow_system_token" => true,
		], $options);

		// init
		$this->name = "address";
		$this->resource = "address";
		$this->key_field = "add_id";
		$this->name_field = "add_name";
		$this->parent_field = "add_ref_address";
		$this->code_field = "add_id";

		// fields
		$this->fields = \com\data\fields::make()
			->add("add_id"			, "id"			, \com\data\type\tkey::make())
			->add("add_name"		, "name"		, \com\data\type\tstring::make())
			->add("add_type"		, "type"		, \com\data\type\tlist::make())
			->add("add_nomination"	, "nomination"	, \com\data\type\tlist::make())

			->add_reference("add_ref_suburb"	, "suburb"	, "suburb")
			->add_reference("add_ref_town"		, "town"	, "town")
			->add_reference("add_ref_province"	, "province", "province")
			->add_reference("add_ref_country"	, "country"	, "country")
			->add_reference("add_ref_address"	, "address"	, "address")

			->add("add_unitnr"			, "unitnr"			, \com\data\type\tstring::make())
			->add("add_floor"			, "floor"			, \com\data\type\tstring::make())
			->add("add_building"		, "building"		, \com\data\type\tstring::make())
			->add("add_farm"			, "farm"			, \com\data\type\tstring::make())
			->add("add_streetnr"		, "streetnr"		, \com\data\type\tstring::make())
			->add("add_street"			, "street"			, \com\data\type\tstring::make())
			->add("add_development"		, "development"		, \com\data\type\tstring::make())
			->add("add_attention"		, "attention"		, \com\data\type\tstring::make())
			->add("add_pobox"			, "P.O. Box"		, \com\data\type\tstring::make())
			->add("add_postnet"			, "postnet"			, \com\data\type\tstring::make())
			->add("add_privatebag"		, "privatebag"		, \com\data\type\tstring::make())
			->add("add_clusterbox"		, "cluster box"		, \com\data\type\tstring::make())
			->add("add_line1"			, "line1"			, \com\data\type\tstring::make())
			->add("add_line2"			, "line2"			, \com\data\type\tstring::make())
			->add("add_line3"			, "line3"			, \com\data\type\tstring::make())
			->add("add_line4"			, "line4"			, \com\data\type\tstring::make())
			->add("add_code"			, "code"			, \com\data\type\tstring::make())
			->add("add_gps_lat"			, "gps latitude"	, \com\data\type\tstring::make())
			->add("add_gps_lng"			, "gps longitude"	, \com\data\type\tstring::make())
			->add("add_raw"				, "raw address"		, \com\data\type\ttext::make())
		;

		// storage
		$this->storage = \com\data\storage\dbt::with($this, \core::dbt("address"));
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function get_obj() {
		return \app\data\obj\address::with($this);
	}
	//--------------------------------------------------------------------------------
}