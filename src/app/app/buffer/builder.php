<?php

namespace LiquidedgeApp\Octoapp\app\app\buffer;

/**
 * Model: file
 *
 * @package scorpion3
 * @subpackage model
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *///--------------------------------------------------------------------------------
class builder extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

    /**
     * @var \DOMDocument
     */
    protected $dom;
    public $var = '$buffer';

    protected $html_arr = [];
    protected $indent_count = 0;

 	//--------------------------------------------------------------------------------
 	// functions
	//--------------------------------------------------------------------------------
    public function __construct() {
        $this->dom = new \DOMDocument();
    }
    //--------------------------------------------------------------------------------
    public function add_html($html) {
        $this->html_arr[] = $html;
    }
    //--------------------------------------------------------------------------------
    public function build($options = []) {

    	$options = array_merge([
    	    "wrap" => true
    	], $options);

        @$this->dom->loadHTML(\LiquidedgeApp\Octoapp\app\app\str\str::sanitize_html(implode(" ", $this->html_arr)));

        $html = "";
        $this->init($this->dom, $html);

        $html = str_replace('" => true, ".', " ", $html);

        if(!$options["wrap"]) return $html;

        return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->code(["*" => $html]);
    }
    //--------------------------------------------------------------------------------
    private function build_options(&$options = []) {
        $return = "";

		foreach ($options as $option_index => $option_item) {

            switch ($option_index) {
                case "@class":
                    $class_parts = explode(" ", $option_item);
                    foreach ($class_parts as $class) $return .= '".' . $class . '" => true, ';
                    break;

                default:
                    $return .= '"' . $option_index . '" => "' . $option_item . '", ';
            }
        }

        return $return;
    }
    //--------------------------------------------------------------------------------
    private function init(\DOMNode $domNode, &$html) {

		$fn_get_tabs = function($indent_count){
			$str = "";
			for ($i = 1; $i <= $indent_count; $i++) {
				$str .= "\t";
			}
			return $str;
		};

        foreach ($domNode->childNodes as $node){
            if(!property_exists($node, "tagName")) continue;

            $data = [];
            $tagname = $node->tagName;

            $txt = false;
            foreach (range(0, $node->childNodes->length - 1) as $idx) {
                if($node->childNodes->item($idx) && property_exists($node->childNodes->item($idx), "nodeType")){
                    if ($node->childNodes->item($idx)->nodeType == 3) {
                        $txt .= $node->childNodes->item($idx)->nodeValue;
                    }
                }
            }

            $txt = trim($txt);

            if ($node->hasAttributes()) {
                foreach ($node->attributes as $attr) {
                    $name = $attr->nodeName;
                    $value = $attr->nodeValue;
                    $data["@$name"] = $value;
                }
            }

            $options_str = $this->build_options($data);
            if($options_str != "") $options_str = "[{$options_str}]";

            if(!in_array($tagname, ["body", "html"])){
				if($node->hasChildNodes()){
					$this->build_options($data);
					$this->indent_count++;
					$html .= $fn_get_tabs($this->indent_count).$this->var.'->'.$tagname.'_('.$options_str.');'."\n";
				}else{
					$html .= $fn_get_tabs($this->indent_count).$this->var.'->'.$tagname.'('.$options_str.');'."\n";
				}
			}

            if($node->hasChildNodes()) {
                $this->init($node, $html);
            }

            if(!in_array($tagname, ["body", "html"])){
				if($txt){
					$html .= $fn_get_tabs($this->indent_count).$this->var.'->add("'.$txt.'");'."\n";
				}

				if($node->hasChildNodes()){
					$html .= $fn_get_tabs($this->indent_count).$this->var.'->_'.$tagname.'();'."\n";
					$this->indent_count--;
				}
			}
        }

    }
    //--------------------------------------------------------------------------------
}