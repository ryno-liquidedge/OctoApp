<?php

namespace LiquidedgeApp\Octoapp\app\app\os\filetype;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class webp implements \com\os\int\filetype {
	//--------------------------------------------------------------------------------
	use \com\os\tra\filetype;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "WEBP";
		$this->extension = "webp";
		$this->mimetype = "image/webp";
	}
	//--------------------------------------------------------------------------------
	// methods
	//--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return static
     */
	public static function make($options = []) {
		// options
		$options = array_merge([
		], $options);

		// done
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}
