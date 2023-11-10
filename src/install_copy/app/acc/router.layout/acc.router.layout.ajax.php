<?php

namespace acc\router\layout;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class ajax extends \com\router\layout\ajax {
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @return \acc\router\layout\ajax
	 */
	public static function make($options = []) {
		return new \acc\router\layout\ajax($options);
	}
	//--------------------------------------------------------------------------------
}