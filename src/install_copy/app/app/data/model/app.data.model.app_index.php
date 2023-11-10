<?php

namespace app\data\model;

/**
 * @package app\data\model
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class app_index extends \com\core\model\app_index {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		// init
		$this->name = "Application index";
		$this->resource = "app_index";
		$this->key_field = "app_id";
		$this->name_field = "app_name";
		$this->add_cache(\com\cache\provider\local::make(), "general");
		$this->add_cache(\com\cache\provider\local::make(), "id");

		// fields
		$this->fields = \com\data\fields::make()
			->add("app_id", "database id", \com\data\type\tkey::make())
			->add("app_name", "name", \com\data\type\tstring::make())
			->add("app_type", "type", \com\data\type\tlist::make())
		;

		// storage
		$this->storage = \com\data\storage\dbt::with($this, \core::dbt("app_index"));
	}
	//--------------------------------------------------------------------------------
}