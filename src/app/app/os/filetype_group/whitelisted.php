<?php

namespace LiquidedgeApp\Octoapp\app\app\os\filetype_group;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class whitelisted extends \com\os\filetype_group\whitelisted {
	protected function __construct($options = []) {
		// init
		$this->name = "All whitelisted files";

		// file types
		$this->filetypes = [];

		// add all available whitelisted classes
		$class_arr = \LiquidedgeApp\Octoapp\app\app\os\filetype::get_available_classes();
		foreach ($class_arr as $class_item) {
			$filetype = \LiquidedgeApp\Octoapp\app\app\os\filetype::make($class_item);
			if (!$filetype->is_whitelisted()) continue;

			$this->filetypes[] = $filetype;
		}
	}
	/**
	 * @param type $options
	 * @return app\os\filetype\whitelisted
	 */
	public static function make($options = []) {
		// options
		$options = array_merge([
		], $options);

		// done
		return new \LiquidedgeApp\Octoapp\app\app\os\filetype_group\whitelisted($options);
	}
}
