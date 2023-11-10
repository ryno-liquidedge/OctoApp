<?php

namespace acc\router\region\system;

/**
 * @package acc\router\region\system
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class navbar implements \com\router\int\region {
	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {
		// navbar
		$navbar = \com\ui::make()->navbar([
		    "/container" => [".container-fluid bg-dark" => true],
        ]);
		\events::on_navbar($navbar);

		// done
		return $navbar->build(["/collapse" => [".navbar-nav me-auto py-1" => true],]);
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @return navbar
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}