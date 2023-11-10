<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class source extends \com\core\db\source {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------

 	//--------------------------------------------------------------------------------
	// functions
 	//--------------------------------------------------------------------------------
	public function on_insert(&$source) {
		// duplicate check
		if ($source->sou_code) {
			$duplicate_source = $this->find([".sou_code" => $source->sou_code]);
			if ($duplicate_source) return false;
		}
	}
 	//--------------------------------------------------------------------------------
}