<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\intf;

/**
 * Interface.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
abstract class element extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// interface
	//--------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------
	// internal
	//--------------------------------------------------------------------------------
    /**
     * @param $buffer \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\buffer
     * @param array $options
     * @return mixed
     */
	public function assemble(&$buffer, $options = []) {
		return $buffer->add($this->build($options));
	}
	//--------------------------------------------------------------------------------
	public function flush($options = []) {
		echo $this->build($options);
	}
	//--------------------------------------------------------------------------------
}