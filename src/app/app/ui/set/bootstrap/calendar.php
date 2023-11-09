<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class calendar extends \com\ui\intf\element {

	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
    protected $options = [];

	protected $disabled_dates_arr = [];
	protected $repeating_events_arr = [];
	protected $source_url;
	
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Calendar";
		$this->options = $options;
	}
	//--------------------------------------------------------------------------------
    public function add_disabled_date($date) {
        $this->disabled_dates_arr[$date] = $date;
    }
	//--------------------------------------------------------------------------------
    public function add_repeating_event($weekday, $options = []) {
        $options = array_merge([
            "disabled" => false,
            "daysOfWeek" => \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($weekday),
            "title" => false,
        ], $options);

        $class_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::extract_signature_items(".", $options);
        foreach ($class_arr as $classname => $value){
            if($value) $options["classNames"][] = $classname;
            unset($options[".$classname"]);
        }

        $this->repeating_events_arr[] = $options;
    }
	//--------------------------------------------------------------------------------
    public function set_source_url($url) {
        $this->source_url = $url;
    }
	//--------------------------------------------------------------------------------
    public function build($options = []) {

	    $options = array_merge([
	        "id" => false,
	        "url" => $this->source_url,
	    ], $options, $this->options);

	    $id = $options["id"];

        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

        $options[".fullcalendar-calendar"] = true;
        $buffer->div_([".calendar-wrapper position-relative day-calendar min-h-50vh overflow-auto overflow-auto" => true, "@data-id" => $id]);
            $buffer->div_([".calendar-loader" => true]);
                $buffer->div_(["@style" => "left: 0; top: 0; width: 100%; height: 100%; position: absolute; background: #FFF; z-index: 99; display: flex; align-items: center; justify-content: center; color: #000;"]);
                    $buffer->span(["*" => "Loading...", ".me-2" => true])->xicon("fa-spinner", [".fa-spin" => true]);
                $buffer->_div();
            $buffer->_div();

            $buffer->div([".calendar min-w-500px" => true, "@id" => $id]);
            $buffer->div([".calendar-input-wrapper" => true, "@data-id" => $id]);
        $buffer->_div();

        $js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options([
            "*id" => $id,
            "*disabled_dates_arr" => $this->disabled_dates_arr,
            "*repeating_events_arr" => $this->repeating_events_arr,
        ]);

        $buffer->script(["*" => "
            var {$id};
            $(function(){
                {$id} = new calendar({$js_options});
                {$id}.ajax('{$options["url"]}', {data:{_csrf:'".\core::$app->get_response()->get_csrf()."'}});
                {$id}.load();
            });
        "]);

        return $buffer->build();

    }
    //--------------------------------------------------------------------------------
    public static function get_event_options($options = []): array {
        $options = array_merge([
            "id" => false,
            "date" => false,
            "start" => false,
            "end" => false,
            "allDay" => true,
            "display" => 'background',
            "disabled" => false,
            "forceRender" => true,
            "classNames" => [],
            "eventSource" => "ajax", // new | init | ajax
        ], $options);

        if($options["date"]){
            $options["start"] = $options["date"];
            $options["end"] = $options["date"];
        }

        $class_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::extract_signature_items(".", $options);
        foreach ($class_arr as $classname => $value){
            if($value) $options["classNames"][] = $classname;
            unset($options[".$classname"]);
        }

        return $options;
    }
    //--------------------------------------------------------------------------------
}