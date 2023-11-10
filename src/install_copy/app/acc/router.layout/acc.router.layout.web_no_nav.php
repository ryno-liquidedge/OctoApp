<?php

namespace acc\router\layout;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class web_no_nav extends \com\router\layout\web {
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
			\acc\router\region\website\head::make(),
		];

		// body
		$this->region_body_arr = [
			\acc\router\region\website\banner::make(),
			\acc\router\region\website\body::make($options),
			\acc\router\region\website\footer::make(),
			\acc\router\region\website\scripts::make(),
			\acc\router\region\website\errors::make(),
			\com\router\region\messages::make(),
		];
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
     * @param array $options
     * @return \com\router\layout\web|static
     */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}