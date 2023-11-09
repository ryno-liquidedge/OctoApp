<?php

namespace LiquidedgeApp\Octoapp\app\app\context;

/**
 * Provides a means to hide and show content based on the user's role, the system's
 * environment or custom defined subclasses of resources. For instance client and user
 * would be different subclasses of person and may require different fields to be shown
 * on the edit and add pages.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class context extends \com\context {
	/**
	 * @var array
	 */
	protected $context_item_arr = [];
 	//--------------------------------------------------------------------------------
	/**
	 * @return array
	 */
	public function get_context_item_arr(): array {
		return $this->context_item_arr;
	}
 	//--------------------------------------------------------------------------------
    public function is_empty() {
        return $this->context_item_arr ? false : true;
    }
 	//--------------------------------------------------------------------------------
	public function filter_default($id, $value, $glue = false, $options = []) {
		parent::filter_default($id, $value, $glue, $options);

		$this->context_item_arr["default"]["type"] = "default";
		$this->context_item_arr["default"][$id] = $value;

	}
	//--------------------------------------------------------------------------------
	public function filter_type($type, $id, $value, $glue = false, $options = []) {
		parent::filter_type($type, $id, $value, $glue, $options);

		$this->context_item_arr[$type]["type"] = $type;
		$this->context_item_arr[$type][$id] = $value;
	}
	//--------------------------------------------------------------------------------
}