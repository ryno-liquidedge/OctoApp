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


        $solid_class_arr = \app\solid\property_set\incl\library::$solid_arr;
        $folder_arr = [];
        array_filter($solid_class_arr, function($item)use(&$folder_arr){
        	$classname_parts = explode("\\", $item["classname"]);
        	$basename = $classname_parts[sizeof($classname_parts)-2];
        	$label = \com\str::propercase_name(str_replace("_", " ", $basename));
        	$folder_arr[$basename] = $label;
		});


		foreach ($folder_arr as $folder => $label){
        	$tab->add_tab($label, "?c=developer.solid_property_library/vlist&folder={$folder}");
		}

		$html->display($tab);

    }
	//--------------------------------------------------------------------------------
}
