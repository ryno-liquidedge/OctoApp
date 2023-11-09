<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iswitch extends \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\icheckbox {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Switch input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"switch" => true,
            "/wrapper" => [".align-items-center" => true, ".mb-2" => false,],
		], $options);

		// done
		return parent::build($options);
	}
	//--------------------------------------------------------------------------------
}