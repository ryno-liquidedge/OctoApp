<?php

namespace acc\router\layout;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class clean extends \com\router\layout\clean {
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @return \acc\router\layout\clean
	 */
	public static function make($options = []) {
		return new \acc\router\layout\clean($options);
	}
	//--------------------------------------------------------------------------------
}