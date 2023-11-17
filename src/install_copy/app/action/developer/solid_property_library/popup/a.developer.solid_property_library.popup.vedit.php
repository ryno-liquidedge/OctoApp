<?php

namespace action\developer\solid_property_library\popup;

/**
 * Class vlist
 * @package action\system\setup\property_config
 * @author Ryno Van Zyl
 */

class vedit implements \com\router\int\action {

	protected string $key;

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() { 
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

		$this->key = $this->request->get('key', \com\data::TYPE_STRING, ["get" => true]);
		$solid = \app\solid::get_setting_instance($this->key);
		$data = $solid->get_generated_data_entry();

		$buffer = \app\ui::make()->html_buffer();
		$buffer->form("");

		$buffer->div_([".container-fluid" => true]);
			$buffer->div_([".row" => true]);
				$buffer->div_([".col-12" => true]);
					$ul = \app\ui::make()->ul();
					$ul->add_li(\app\ui::make()->copy_text($data["filename"]), "Filename:");
					$ul->add_li($data["classname"], "Classname:");
					$ul->add_li($data["key"], "Key:");
					$ul->add_li($data["code"], "Code:");
					$buffer->add($ul->build());
					$buffer->xisetting($this->key, ["label_col" => 12]);
				$buffer->_div();
			$buffer->_div();

			$buffer->div_([".row" => true]);
				$buffer->div_([".col-12 text-end" => true]);
					$buffer->button("Close", "core.browser.close_popup()", [".btn-secondary" => true]);
					$buffer->submit_button_js([".me-2" => false]);
				$buffer->_div();
			$buffer->_div();
		$buffer->_div();

		$buffer->flush();


    }
	//--------------------------------------------------------------------------------
}
