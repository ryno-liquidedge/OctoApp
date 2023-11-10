<?php

namespace acc\router\region\website;

/**
 * @package acc\router\region\website
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class footer implements \com\router\int\region {

	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {

        $buffer = \com\ui::make()->buffer();
        $buffer->xscrolltotop();

		// done
		return $buffer->build();
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