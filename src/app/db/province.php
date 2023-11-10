<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class province extends \com\core\db\province {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

 	//--------------------------------------------------------------------------------
	// functions
 	//--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return true;
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return true;
    }
    //--------------------------------------------------------------------------------
	public function on_delete(&$obj) {
    	message(false);

    	//update all addresses
		\core::db()->query("UPDATE address SET add_ref_province = NULL WHERE add_ref_province = {$obj->id};");

		$town_arr = \core::dbt("town")->get_fromdb("tow_ref_province = ".dbvalue($obj->id), ["multiple" => true]);
		foreach ($town_arr as $town){
			$town->delete();
		}

    	message(true);
	}
 	//--------------------------------------------------------------------------------
}