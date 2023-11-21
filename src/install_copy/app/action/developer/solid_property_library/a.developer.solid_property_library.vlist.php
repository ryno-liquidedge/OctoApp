<?php

namespace action\developer\solid_property_library;

/**
 * Class vlist
 * @package action\system\setup\property_config
 * @author Ryno Van Zyl
 */

class vlist implements \com\router\int\action {

	protected string $folder;

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() { 
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

		$this->folder = $this->request->get('folder', \com\data::TYPE_STRING, ["get" => true]);

		$list = \app\ui::make()->table(false, ["/table" => [".align-middle" => true]]);
		$list->page_size = 40;
		$list->item_arr = $this->get_item_arr();
		$list->quickfind_field = "findstring";


		// fields
		$list->add_field("Name", "name");
		if($this->folder == "settings"){
			$list->add_field("Value", "id", ["function" => function($content, $item_index, $field_index, $list){
				$item = $list->item_arr[$item_index];
				$solid = $item["instance"];

				if(in_array($solid->get_data_type(), [\com\data::TYPE_TEXT, \com\data::TYPE_HTML])){
					return \app\ui::make()->readmore($item["value"]);
				}
				return $item["value"];

			}]);
		}

		$list->add_field("Data Type", "id", ["function" => function($content, $item_index, $field_index, $list){
			$item = $list->item_arr[$item_index];
			return $item["instance"]->get_data_type_str();
		}]);

		$list->add_field("", "id", ["function" => function($content, $item_index, $field_index, $list){
			$item = $list->item_arr[$item_index];

			$dropdown = \app\ui::make()->dropdown();
			$dropdown->add_button("Copy Filename", "core.util.copy_text('{$item['filename']}')", [".text-dark" => true]);
			$dropdown->add_button("Copy Code", "core.util.copy_text('{$item['code']}')", [".text-dark" => true]);
			$dropdown->add_button("Copy Key", "core.util.copy_text('{$item['key']}')", [".text-dark" => true]);
			if($this->folder == "settings"){
				$dropdown->add_button("Edit", \app\ui::make()->js_popup("?c=developer.solid_property_library.popup/vedit&key={$item["key"]}", [
					"*width" => "modal-xl",
					"*title" => "Edit Setting",
				]), [".text-dark" => true]);

			}
			return \app\ui::make()->button(false, $dropdown, ["icon" => "fa-bars", ".btn-outline-primary btn-sm" => true]);
		}, "#width" => "1%"]);


		$html = \com\ui::make()->html();
		$html->display($list);

    }
	//--------------------------------------------------------------------------------
    public function get_item_arr() {

		$solid_class_arr = \app\solid\property_set\incl\library::$solid_arr;
		$find_string = "\\solid\\property_set\\solid_classes\\{$this->folder}";

		$return_arr = [];
		array_filter($solid_class_arr, function($item)use($find_string, &$return_arr){
			if(strpos($item["classname"], $find_string) !== false){
				$solid = $this->folder == "settings" ? \app\solid::get_setting_instance($item["key"]) : \app\solid::get_instance($item["key"]);

				$item["id"] = $item["key"];
				$item["name"] = $solid->get_display_name();
				$item["value"] = $this->folder == "settings" ? $solid->get_value() : $solid->get_default();
				$item["findstring"] = $item["name"].$item["value"].$item["key"].$item["filename"].$item["classname"];
				$item["instance"] = $solid;

				$return_arr[$item["key"]] = $item;

			}
		});

        return $return_arr;
    }
	//--------------------------------------------------------------------------------
}
