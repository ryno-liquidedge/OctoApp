<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iproperty extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;

    /**
     * @var \LiquidedgeApp\Octoapp\app\app\ui\solid\property_set\solid_classes\settings\intf\standard
     */
	protected $solid;

	protected $dbentry;

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "DB Property Solid input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"dbentry" => false,
			"key" => false,
			"value" => false,
			"value_option_arr" => [],
			"label" => false,
			"required" => false,
			"label_html" => true,
		], $options);

		$this->dbentry = $options["dbentry"];
		$this->solid = \LiquidedgeApp\Octoapp\app\app\ui\solid::get_instance($options["key"]);

		if($options["value"] === false) $options["value"] = $this->dbentry->get_prop($options["key"]);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->xihidden("{$this->dbentry->get_property_table()}[{$this->solid->get_form_id()}]", $this->solid->get_key());
		$buffer->add($this->build_input($options));

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
	protected function build_input($options) {

		// config
        $field = $this->solid->get_form_id();
        $value = $this->solid->parse($options["value"]);

		// label
		if ($options["label"] === false) $options["label"] = ucfirst($this->solid->get_display_name());
		$label = ucfirst($options["label"]);

		// display input based on db field type
		if($field == "product_property_number_of_units"){
			view($field." = ".$this->solid->get_data_type());
			view($field." = ".$this->solid->get_data_type());
		}
		switch ($this->solid->get_data_type()) {

			// select
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ENUM       :
  				if(!$options["value_option_arr"]) $options["value_option_arr"] = [null => "-- Not Selected --"] + $this->solid->get_data_arr();
  				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iselect($field, $options["value_option_arr"], $value, $label, $options);
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_BOOL       : return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iradio($field, [0 => "No", 1 => "Yes"], $value ? 1 : 0, $label, $options);

			// date
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_DATE		: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->idate($field, $value, $label, $options);
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_YEARMONTH	: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iyearmonth($field, $value, $label, $options);
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_DATETIME	: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->idatetime($field, $value, $label, $options);
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_TIME		: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itime($field, $value, $label, $options);

			// text
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_BYTES 		:
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_SECONDS 	:
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_MINUTES 	:
  				if($value == 0) $value = false;
				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["limit" => "numeric", "@placeholder" => "0"], $options));

			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_INT 		:
  				if($value == 0) $value = false;
				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icounter($field, $value, array_merge(["limit" => "numeric", "label" => $label, "@placeholder" => "0"], $options));

  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_FLOAT 		:
  				if($value == 0) $value = false;
				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["limit" => "fraction", "@placeholder" => "0"], $options));

  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING     : return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, $options);
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_EMAIL		: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["limit" => "email"], $options));
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_TELNR 		: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, ($value == "  " ? false : $value), $label, array_merge([".ui-itel" => true], $options));
  			
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_JSON 		:
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_XML 		:
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_TEXT 		:
  				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["rows" => 5], $options));

  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_HTML 		:
  			    if(!isset($options["wysiwyg"]))$options["wysiwyg"] = true;
  			    $options["allow_tag_arr"] = ["a", "iframe"];
  				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["rows" => 5], $options));

  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_MILISECONDS	:
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_DURATION	:
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_PERCENTAGE :
  			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_CURRENCY :
  				if($value == 0) $value = false;
				if ($value != "0.0") {
					if (preg_match("/^\\./i", $value)) $value = "0{$value}";
					if (preg_match("/\\.[0-9]*0$/i", $value)) $value = rtrim($value, "0");
					if (preg_match("/\\.$/i", $value)) $value = rtrim($value, ".");
				}
				else $value = "0";

				switch ($this->solid->get_data_type()) {
					case DB_MILISECONDS	:
					case DB_DURATION :
						return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, $options);

					case DB_DECIMAL	: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["limit" => "fraction"], $options));
					case DB_PERCENTAGE : return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->ipercentage($field, $value, $label, $options);
					case DB_CURRENCY : return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icurrency($field, $value, $label, $options);
				}
				break;

			case DB_KEY :
				break;

			// unknown
  			default :
				\com\error::create("Unsupported DB_x ( {$this->solid->get_data_type()} )");
				break;
		}
	}
	//--------------------------------------------------------------------------------
}