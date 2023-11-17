<?php

namespace action\developer\solid_property_library;

/**
 * Class vlist
 * @package action\system\setup\property_config
 * @author Ryno Van Zyl
 */

class vtab implements \com\router\int\action {

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() { 
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {


		// html
        $html = \com\ui::make()->html();
        $html->header(1, "Solid Property Library");

        // tabs
        $tab = \app\ui::make()->tab(["id" => "solid_property_tab"]);

        $solid_classes_folder_arr = glob(\core::$folders->get_app_app()."/solid/property_set/solid_classes/*");
		foreach ($solid_classes_folder_arr as $solid_classes_folder){
			$label = \com\str::propercase_name(str_replace("_", " ", basename($solid_classes_folder)));
        	$tab->add_tab($label, "?c=developer.solid_property_library/vlist&folder=".basename($solid_classes_folder));
		}

		$html->display($tab);

    }
	//--------------------------------------------------------------------------------
}
