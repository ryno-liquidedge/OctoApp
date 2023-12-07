<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class menu_left extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	protected $options = null;
	protected $header = [];

    /**
     * @var \LiquidedgeApp\Octoapp\app\app\ui\context
     */
	protected $context;

	protected int $start_tab = 0;
	protected $append;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	/**
	 * Creates the component.
	 *
	 */
    public function __construct($options = []) {
		// options
		$options = array_merge([
			"id" => "manage_panel",
			"disable_collapse" => false,
			"disable_headers" => false,
		], $options);

		$this->name = "Menu Left";
		$this->options = $options;
		$this->context = new \LiquidedgeApp\Octoapp\app\app\context\context();

		// id
		$this->id = ($options["id"] ? $options["id"] : \com\session::$current->session_uid);
    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function set_start_tab($index) {
        $this->start_tab = $index;
    }
	//--------------------------------------------------------------------------------
	/**
	 * @param string $append
	 */
	public function append_menu($append): void {
		$this->append = $append;
	}
	//--------------------------------------------------------------------------------
    public function add_item($id, $label, $control, $options = []) {

        $options = array_merge([
            "icon" => false,
            "panel" => false,
            "is_default" => true,
            "!click" => false,
            "context" => $id,
            "/js" => [],
        ], $options);

        $control = str_replace(["index.php?c=", "?c="], "", $control);

        if(!isset($this->context->get_context_item_arr()["default"]) && $options["is_default"]){
            $this->context->filter_default("control", $control."&context={$options["context"]}");
            $this->context->filter_default("label", $label);
            $this->context->filter_default("icon", $options["icon"]);
            $this->context->filter_default("options", $options);
        }

        $this->context->filter_type($id, "control", $control."&context={$options["context"]}");
        $this->context->filter_type($id, "label", $label);
        $this->context->filter_type($id, "icon", $options["icon"]);
        $this->context->filter_type($id, "options", $options);
        if($options["panel"]) $this->context->filter_type($id, "panel", $options["panel"]);

    }
	//--------------------------------------------------------------------------------
    private function init_start_tab(){
        $get_context_item_arr = $this->context->get_context_item_arr();
        $context_arr = array_values($get_context_item_arr);

        if($this->context->get_type()){
            foreach ($get_context_item_arr as $index => $context_item){
                if($index == $this->context->get_type()){
                    $this->context->filter_default("control", $context_item["control"]);
                    $this->context->filter_default("label", $context_item["label"]);
                    $this->context->filter_default("icon", $context_item["icon"]);
                }
            }
        }else{
            $start_index = $this->start_tab+2;
            if(isset($context_arr[$start_index])){
                $this->context->filter_default("control", $context_arr[$start_index]["control"]);
                $this->context->filter_default("label", $context_arr[$start_index]["label"]);
                $this->context->filter_default("icon", $context_arr[$start_index]["icon"]);
            }
        }

    }
	//--------------------------------------------------------------------------------
	public function build($options = []) {

        $options = array_merge([
            "id" => $this->id,
            "\col-left" => [".col-12 col-sm-4 col-md-3 col-lg-2" => true],
            "\col-right" => [".col-12 col-sm-8 col-md-9 col-lg-10" => true],
            "append" => $this->append,
        ], $options, $this->options);

        $this->context->filter_default("panel", $options["id"]);
        $this->init_start_tab();

		$panel_buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->panel_buffer("?c={$this->context->control}", ["id"=> $this->context->panel]);
    	$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

    	$buffer->div_([".container-fluid" => true]);
            $buffer->div_([".row" => true]);
                $buffer->div_($options["\col-left"]);
                    $context_arr = $this->context->get_context_item_arr();
                    if($context_arr){
                        $menu = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->menu(["id" => "{$options["id"]}_menu"]);
                        foreach ($context_arr as $key => $context_item){
                            if(in_array($key, ["0", "default"])) continue;

							$item_options = array_merge([".active" => $context_item["control"] == $this->context->control], $context_item["options"]);

                            if($context_item["options"]["!click"]){
                            	$js = $context_item["options"]["!click"];
							}else{
								$panel = isset($context_item["panel"]) ? $context_item["panel"] : $context_arr["default"]["panel"];
								$js_options = [];
								$js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options(array_merge($js_options, $context_item["options"]["/js"]));

								$js = "{$panel}.requestUpdate('?c={$context_item["control"]}', $js_options)";
							}

                            $menu->add_link($context_item["label"], $js, $context_item["icon"], $item_options);
                        }
                        $buffer->add($menu->build());
                    }else{
                        $buffer->xmessage("No menus added");
                    }

                    if($options["append"]){
                    	$append_html = $options["append"];
                    	if(!is_string($options["append"]) && is_callable($options["append"])){
                    		$append_html = $options["append"]();
						}
						$buffer->add($append_html);
					}

                $buffer->_div();


                $buffer->div_($options["\col-right"]);
                    $buffer->add( $panel_buffer->build() );
                $buffer->_div();
            $buffer->_div();
    	$buffer->_div();

    	return $buffer->build();
	}
  	//--------------------------------------------------------------------------------
    public function flush() {
        echo $this->build();
    }
  	//--------------------------------------------------------------------------------
}