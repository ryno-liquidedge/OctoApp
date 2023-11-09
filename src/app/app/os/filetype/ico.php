<?php

namespace LiquidedgeApp\Octoapp\app\app\os\filetype;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class ico implements \com\os\int\filetype {
	//--------------------------------------------------------------------------------
	use \com\os\tra\filetype;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Icon";
		$this->extension = "ico";
		$this->mimetype = "image/x-icon";
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
