<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

use com\release;
/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class tooltip extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	protected $run_once = false;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Tooltip";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// we dont need to run this more than once
		if ($this->run_once) return;
		$this->run_once = true;

		// use javascript to add tooltip
		\LiquidedgeApp\Octoapp\app\app\js\js::add_script("
			var tooltipList = [].slice.call(document.querySelectorAll('[title]'));
			tooltipList.map(function (el) {
			  return bootstrap.Tooltip.getOrCreateInstance(el, {html:true});
			});
		");
	}
	//--------------------------------------------------------------------------------
}