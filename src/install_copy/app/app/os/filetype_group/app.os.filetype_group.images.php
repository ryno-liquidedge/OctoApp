<?php

namespace app\os\filetype_group;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class images extends \com\os\filetype_group\images {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Images";

		// file types
		$this->filetypes = [];
		$this->filetypes[] = \com\os\filetype\jpg::make();
		$this->filetypes[] = \com\os\filetype\jpeg::make();
		$this->filetypes[] = \com\os\filetype\gif::make();
		$this->filetypes[] = \com\os\filetype\png::make();
		$this->filetypes[] = \app\os\filetype\webp::make();
	}
}
