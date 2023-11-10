<?php

namespace acc\router\layout;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class system_web extends \com\router\layout\system {
	//--------------------------------------------------------------------------------
	use \com\router\tra\layout;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
			"content" => false,
		], $options);

		// head
		$this->region_head_arr = [
			\acc\router\region\system\head::make(),
		];

		// body
		$this->region_body_arr = [
			\acc\router\region\system\body::make($options),
			\acc\router\region\system\scripts::make(),
			\acc\router\region\system\errors::make(),
			\com\router\region\messages::make(),
		];
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return \com\router\layout\system|static
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}