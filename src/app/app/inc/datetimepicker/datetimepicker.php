<?php
namespace LiquidedgeApp\Octoapp\app\app\inc\datetimepicker;

/**
 * Model: string
 *
 * https://github.com/tempusdominus/bootstrap-4
 * https://getdatepicker.com/5-4/Installing/

 *///--------------------------------------------------------------------------------
class datetimepicker extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
 	//--------------------------------------------------------------------------------
 	// properties
	//--------------------------------------------------------------------------------
    protected $buffer;
 	//--------------------------------------------------------------------------------
 	// functions
	//--------------------------------------------------------------------------------
    public function __construct() {
        $this->buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
    }
    //--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"value" => false,
			"label" => false,

			// basic
			"!change" => false,
			"@disabled" => false,

			"value_format" => "Y-m-d G:i:s",
			"@data-js-format" => "YYYY-MM-DD HH:mm",

			// advanced
			"width" => false,
			"hidden" => false,
			"start_date" => false,
			"end_date" => false,

			"disabled_time_intervals" => [],
			"disabled_days_of_week" => [],
			"disabled_dates" => [],
			"disabled_hours" => [],

			"time_picker_auto_switch" => false,
			"min_view" => "days",

			// form-input
			"help" => false,
			"required" => false,
			"prepend" => false,
			"append" => false,
			"wrapper_id" => false,
			"label_width" => false,
			"label_col" => false,
			"label_html" => false,

			"icon" => "fa-calendar",
			"/icon" => [],

			"/js" => [],
		],$options);

		// init
		$id = $options["id"];
		$value = $options["value"];
		$label = $options["label"];
		$options["wrapper_id"] = "__{$id}wrapper";

		// value
  		$value = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime($value, ($options["value_format"] ?: \core::$app->get_instance()->get_format_date()), "");
  		$JS_id = strtr($id, ["[" => "\\\\[", "]" => "\\\\]", "." => "\\\\."]);
		$name_id = str_replace(["[", "]"], "_", $id);
		$is_time_picker = $options["time_picker_auto_switch"];

		$js_options = [];
		$js_options["*ignoreReadonly"] = false;
		$js_options["*useCurrent"] = false;

		$fn_init_date_arr = function($date_arr){
			$return = [];

			foreach ($date_arr as $date){
				$return [] = "new Date('{$date}')";
			}

			return "![".implode(",", $return)."]";
		};

		$js_options["*disabledTimeIntervals"] = $fn_init_date_arr($options["disabled_time_intervals"]);
		$js_options["*daysOfWeekDisabled"] = array_values($options["disabled_days_of_week"]);
		$js_options["*disabledDates"] = $fn_init_date_arr($options["disabled_dates"]);
		$js_options["*disabledHours"] = $fn_init_date_arr($options["disabled_hours"]);

		$js_options["*format"] = $options["@data-js-format"];
		$js_options["*minDate"] = $options["start_date"] ? \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime($options["start_date"], ($options["value_format"] ?: \core::$app->get_instance()->get_format_date()), "") : false;
		$js_options["*maxDate"] = $options["end_date"] ? \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime($options["end_date"], ($options["value_format"] ?: \core::$app->get_instance()->get_format_date()), "") : false;
		$js_options["*todayHighlight"] = true;
		$js_options["*sideBySide"] = false;
		$js_options["*viewMode"] = $options["min_view"];
		$js_options["*buttons"] = [
			"showToday" => false,
            "showClear" => false,
            "showClose" => false
		];

		$js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options(array_merge($js_options, $options["/js"]));

		if (!$options["hidden"] && !$options["@disabled"]) {
			\LiquidedgeApp\Octoapp\app\app\js\js::add_script("
			
				$(function(){
					$('#{$JS_id}').datetimepicker({$js_options});
					
					$('#{$JS_id}').on('change.datetimepicker', function (e){
					
						".($is_time_picker ? "
							if (!e.oldDate && $('#{$JS_id}').datetimepicker('useCurrent')) {
							  return;
							} else if ( e.oldDate && e.date && (e.oldDate.format('YYYY-MM-DD') === e.date.format('YYYY-MM-DD'))) {
							  {$options["!change"]}
							  return;
							}
							setTimeout(function () { $('#{$options["wrapper_id"]} [data-action=\"togglePicker\"]').click(); }, 150);
						" : $options["!change"])."
					});
					
					$('body').on('click', function(){
						$('#{$JS_id}').datetimepicker('hide');
					});
				});
				
				var $name_id = {
					setDate: function(date){
						$('#{$JS_id}').datetimepicker('date', date);
					},
					setStartDate: function(date){
						$('#{$JS_id}').datetimepicker('minDate', date);
					},
					setEndDate: function(date){
						$('#{$JS_id}').datetimepicker('maxDate', date);
					},
					disableDate: function(date_arr){
						$('#{$JS_id}').datetimepicker('disabledDates', date_arr);
					},
					clear: function(){
						$('#{$JS_id}').datetimepicker('date', null);
					}
				};
			");
		}

		// append
		if(!is_null($options["prepend"])){
			$options["/icon"]["@data-toggle"] = "datetimepicker";
			$options["/icon"]["@data-target"] = "#{$id}";
			$options["/icon"][".me-2"] = false;
			$options["prepend"] = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon($options["icon"], $options["/icon"]);
		}

		// input
		unset($options["!change"]);
		$options["@data-toggle"] = "datetimepicker";
		$options["@data-target"] = "#{$id}";
		$this->buffer->div_([".position-relative w-100" => true]);
			$this->buffer->xitext($id, $value, $label, $options);
		$this->buffer->_div();

		// done
		return $this->buffer->build();

	}
    //--------------------------------------------------------------------------------
}