<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class readmore extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
    protected $length = 200;
    protected $text = false;

    protected $options;

    protected $show_more_icon = false;
    protected $show_more_text = "Read More";

    protected $show_less_icon = false;
    protected $show_less_text = "Read Less";
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {
		// init
		$this->name = "Readmore";
		$this->options = $options;
		$this->id = "readmore_".\core::$app->get_session()->session_uid;

		$this->show_more_icon = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-plus", [".ml-1" => true]);
		$this->show_less_icon = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-minus", [".ml-1" => true]);
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"link_color" => "primary",
			"/link" => [".fs-8" => true],
			"/wrapper" => [],
		], $this->options, $options);

		if(strlen($this->text) <= $this->length) return $this->text;

		$text_part_biginning = substr($this->text, 0, $this->length);
		$text_part_end = substr($this->text, $this->length, strlen($this->text));

		// buffer
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$options["/wrapper"]["@id"] = $this->id;
		$options["/wrapper"][".readmore-wrapper"] = true;

		$buffer->p_($options["/wrapper"]);

            $buffer->add($text_part_biginning);

            $buffer->span([".dots" => true, "*" => $this->length > 0 ? "..." : ""]);
            $buffer->span([".more fade-in fast" => true, "#display" => "none", "*" => $text_part_end]);

            $options["/link"][".link-{$options["link_color"]}"] = true;
            $options["/link"][".ms-2"] = true;
            $options["/link"][".me-1"] = true;
            $options["/link"][".text-nowrap"] = true;
            $options["/link"][".d-block"] = true;
            $options["/link"]["@href"] = "javascript:;";

            $buffer->a_($options["/link"]);
                $buffer->add($this->show_more_icon);
                $buffer->add($this->show_more_text);
            $buffer->_a();

		$buffer->_p();

		// javascript
		$buffer->script("*
		    $('#{$this->id} a').click(function(){
                var element = $(this);
                var dots = $('#{$this->id} .dots');
                var more = $('#{$this->id} .more');
                
                element.hide();
                
                if (!dots.is(':visible')) {
                    element.html('{$this->show_more_icon}{$this->show_more_text}');
                    more.hide();
                    element.show(); 
                    dots.show();
                } else {
                    element.html('{$this->show_less_icon}{$this->show_less_text}');
                    dots.hide();
                    element.show();
                    more.show();
                }
            })
		");

		// done
		return $buffer->get_clean();
	}

	//--------------------------------------------------------------------------------
    /**
     * @param int $length
     */
    public function set_length($length) {
        $this->length = $length;
    }

	//--------------------------------------------------------------------------------
    /**
     * @param bool $text
     */
    public function set_text($text) {
        $this->text = \LiquidedgeApp\Octoapp\app\app\data\data::parse_paragraph($text);
    }

	//--------------------------------------------------------------------------------
}