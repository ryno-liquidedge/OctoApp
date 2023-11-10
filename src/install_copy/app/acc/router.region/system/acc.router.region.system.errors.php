<?php

namespace acc\router\region\system;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class errors implements \com\router\int\region {
	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {
		// only display errors when set to do so
		if (!\core::$app->get_instance()->get_show_errors()) return false;

		// done
		return \app\error::navigation();
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return static
     */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}