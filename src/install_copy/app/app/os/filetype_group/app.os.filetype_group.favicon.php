<?php

namespace app\os\filetype_group;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class favicon implements \com\os\int\filetype_group {
	//--------------------------------------------------------------------------------
	use \com\os\tra\filetype_group;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Favicon";

		// file types
		$this->filetypes = [];
		$this->filetypes[] = \com\os\filetype\png::make();
		$this->filetypes[] = \app\os\filetype\ico::make();
	}
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
