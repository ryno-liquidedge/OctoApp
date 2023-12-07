<?php
namespace LiquidedgeApp\Octoapp\app\app\inc\pagination;
/**
 * @package app\inc\pagination
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class pagination extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Pagination";
		$this->id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("pagination");
	}
    //--------------------------------------------------------------------------------
    public function build($options = []) {

        // options
		$options = array_merge([
		    "total" => 200,
		    "page" => 1,
		    "maxVisible" => 5,
		    "firstLastUse" => true,

		    "next" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-angle-right"),
		    "prev" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-angle-left"),

		    "first" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-angle-double-left"),
		    "last" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-angle-double-right"),

		    "!click" => "function(page){}",

		], $options);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		//init element
		$buffer->div(["@id" => $this->id]);

		//init json options
		$json_options = json_encode($options);

		//apply script
		\LiquidedgeApp\Octoapp\app\app\js\js::add_script("
		    $('#{$this->id}').bootpag($json_options).on('page', function(event, num){
		        var fn = {$options["!click"]};
                if (fn) fn.apply(this, [num]);
            });
		");

		return $buffer->build();

    }
    //--------------------------------------------------------------------------------
}