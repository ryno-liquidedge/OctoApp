<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class html
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class html extends \com\ui\set\bootstrap\html {
	//--------------------------------------------------------------------------------
	public function display($obj) {
		if ($obj instanceof \com\ui\intf\table && $obj->enable_nav) {
			$obj->html = $this;
			$obj->display();
			return;
		}

		// toolbar
		$this->close_toolbar();

		//callable
        if(is_callable($obj)){
            $obj = $obj();
        }

		// string
		if (is_string($obj)) {
			echo $obj;
			return;
		}

		// \com\ui\intf\buffer integration
		if ($obj instanceof \com\ui\intf\buffer) {
			echo $obj->get_clean();
			return;
		}

		// \com\ui\intf\element integration
		if ($obj instanceof \com\ui\intf\element) {
			echo $obj->build();
			return;
		}

		// html
		$obj->display();
	}
	//--------------------------------------------------------------------------------
	public function iaddress($label, $address, $options = []) {

		$options = array_merge([
		    "add_type_arr" => [1 => "Physical"],
		    "input_arr" => [
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "show",
				"show_streetnr_line" => "show",
				"show_attention_line" => "hide",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "show",
				"@disabled" => true,
			],
		], $options);

		// toolbar
		$this->close_toolbar();

		// header
		$this->header(3, $label);

		// html
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iaddress($address, $options);
		$this->space();
	}
	//--------------------------------------------------------------------------------
	public function idate($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->idate($name, $value, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function idatetime($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->idatetime($name, $value, $label, $options);
	}
	//--------------------------------------------------------------------------------
}