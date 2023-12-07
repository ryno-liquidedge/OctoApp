<?php

namespace app\coder\installer;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class base extends \com\coder\installer\base {
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function rebuild_config() {
		$this->code_config();

		$builder = \com\coder\builder\config::make();
		$builder->build();
	}
	//--------------------------------------------------------------------------------
}