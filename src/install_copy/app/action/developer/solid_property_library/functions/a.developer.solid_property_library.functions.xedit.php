<?php

namespace action\developer\solid_property_library\functions;

/**
 * Class vlist
 * @package action\system\setup\property_config
 * @author Ryno Van Zyl
 */

class xedit implements \com\router\int\action {

	protected string $key;

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() { 
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

		$config_arr = [];
        $settings_arr = $this->request->get('settings_arr', \com\data::TYPE_STRING, ["default" => []]);

        foreach ($settings_arr as $id => $key){
            $solid = \app\solid::get_setting_instance($key);
            $value = $this->request->get($id, $solid->get_data_type(), ["default" => $solid->get_default()]);
            $solid->save_value($value);

            $config_arr[$key] = $value;
        }

        return \app\http::ajax_response([
    		"js" => "core.browser.close_all_modals(); solid_property_tab.refresh();"
		]);
    }
	//--------------------------------------------------------------------------------
}
