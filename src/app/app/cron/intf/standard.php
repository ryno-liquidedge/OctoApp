<?php

namespace LiquidedgeApp\Octoapp\app\app\cron\intf;

/**
 * @package app\cron\cron\intf
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

abstract class standard extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	//--------------------------------------------------------------------------------
	abstract protected function on_run($options = []);
    //--------------------------------------------------------------------------------
	public function run($options = []) {
		$this->on_run($options);
	}
    //--------------------------------------------------------------------------------
}
