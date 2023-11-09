<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class buffer extends \com\ui\set\bootstrap\buffer {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __call($name, $arguments) {
		// allow calls to be made directly to com.ui by prefixing the function with a x
		if (substr($name, 0, 1) == "x") {
			// chain-able
			return $this->add(call_user_func_array([\LiquidedgeApp\Octoapp\app\app\ui\ui::make(), substr($name, 1)], $arguments));
		}

		// any standard html tag
		$this->add(call_user_func_array([\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag(), $name], $arguments));

		// check if tag was left open
		$last_detail = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->get_last_detail();
		if (!$last_detail["closed"]) {
			if ($last_detail["tag"]) $this->tag_arr[] = $last_detail["tag"];
		}
		else {
			$last_tag = end($this->tag_arr);
			if ($last_tag == $last_detail["tag"]) array_pop($this->tag_arr);
		}

		// chain-able
		return $this;
	}
	//--------------------------------------------------------------------------------
	public function br($count = 1, $options = []) {

		for ($i = 0; $i <= $count; $i++) {
		    parent::br($options);
		}

		// chain-able
		return $this;
	}
	//--------------------------------------------------------------------------------
}