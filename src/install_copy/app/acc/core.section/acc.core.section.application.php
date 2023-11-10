<?php

namespace acc\core\section;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class application extends \com\core\section\application {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		// init
		$this->name = "Application";
		$this->ui = \app\ui\set\bootstrap::make();
		$this->layout = "system";
	}
	//--------------------------------------------------------------------------------
}