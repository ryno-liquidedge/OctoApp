<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class tag extends \com\ui\intf\tag {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	/**
	 * Saves the last tag that was used.
	 * @var string
	 */
	protected $last_tag = false;
	/**
	 * Saves the close status of the last action.
	 * @var boolean
	 */
	protected $last_closed = false;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Basic HTML tag";
	}
	//--------------------------------------------------------------------------------
	/**
	 * This function will catch all calls to this class. The name of the function
	 * call will be used as the html tag. Using a _ character at the start of the function
	 * name (eg. _div), will build a closing tag. Using a _ character at the end of the
	 * function name (eg. div_) will build a tag without a corresponding closing tag.
	 * When no _ character is either at the start or end of the function name (eg. div),
	 * an open tag with corresponding closing tag will be built.
	 *
	 * @param string|boolean $pattern <p>A pattern that will be parsed into options or false for no parsing.</p>
	 * @param array $options <p>Any option that would be acceptable for the tag() function.</p>
	 *
	 * @return string <p>The tag's html code.</p>
	 */
	public function __call($name, $arguments) {
		// closing tags
		if (substr($name, 0, 1) == "_") {
			$name = substr($name, 1);

			// save last detail
			$this->last_tag = $name;
			$this->last_closed = true;

			// done
			return $this->close($name);
		}

		// arguments
		$pattern = (isset($arguments[0]) ? $arguments[0] : false);
		$options = (isset($arguments[1]) ? $arguments[1] : []);

		if (is_array($pattern)) {
			$options = $pattern;
			$pattern = false;
		}

		// open tag
		// priority: function > options > pattern
		if (substr($name, -1) == "_") {
			$options["/"] = false;
			$name = substr($name, 0, -1);
		}
		else $options["/"] = true;

		// options
		$options = array_merge($this->parse_pattern($pattern), $options);

		// save last detail
		$this->last_tag = $name;
		$this->last_closed = $options["/"];

		// call the tag function
		return $this->tag($name, $options);
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

	}
	//--------------------------------------------------------------------------------
	/**
	 * Returns the tag and close status of the last call made.
	 *
	 * @return array <p>An array with tag and closed indexes.</p>
	 */
	public function get_last_detail() {
		return [
			"tag" => $this->last_tag,
			"closed" => $this->last_closed,
		];
	}
	//--------------------------------------------------------------------------------
	/**
	 * Adds the specified content directly to the buffer. Content is html-encoded by
	 * default.
	 *
	 * @param string $content <p>The content.</p>
	 * @param boolean $options[html] <p>If true, the content will not be html-encoded.</p>
	 *
	 * @return string <p>The HTML-encoded (or not) content.</p>
	 */
	public function content($content, $options = []) {
		// options
  		$options = array_merge([
			"html" => false,
			"br" => false,
  		], $options);

		// save last detail
		$this->last_tag = false;
		$this->last_closed = false;

		// non-encoded
		if ($options["html"]) {
			if ($options["br"]) $content = nl2br($content);
			return $content;
		}

		// encoded
		$content = htmlentities($content, ENT_COMPAT, \core::$app->get_instance()->get_charset());
		if ($options["br"]) $content = nl2br($content);

		// done
		return $content;
	}
	//--------------------------------------------------------------------------------
	// internal
	//--------------------------------------------------------------------------------
	/**
	 * Builds an HTML tag. This function uses an options array with special
	 * meaning if the index starts with a specific character. Using the class(.) option,
	 * multiple classes can be added by providing multiple indexes starting with '.'. They
	 * will be added in the same order they were provided. When using the attribute(@)
	 * option, providing 'true' will use the option-index as the attribute value.
	 *
	 * @param string $tag <p>Any HTML tag.</p>
	 * @param string $options[#] <p>If the option index starts with '#', the rest of the index will be used as an inline style name and the option value as the value. (style="[option-index]:[option-value];")</p>
	 * @param boolean $options[.] <p>If the option index starts with '.' and the option value is 'true', the rest of the index will be added as an inline class. (class="[option-index]")</p>
	 * @param string|boolean $options[@] <p>If the option index starts with '@', the rest of the index will be used as a tag attribute name and the option value as the value. ([option-index]="[option-value]")</p>
	 * @param string $options[!] <p>If the option index starts with '!', the rest of the index will be used as an inline event and the option value as the javascript to execute. (on[option-index]="[option-value]")</p>
	 * @param string $options[^] <p>The option value will be used as the html within the opening and closing tags. This value will be html-encoded. (<tag>[option-value]</tag>)</p>
	 * @param string $options[*] <p>The option value will be used as the html within the opening and closing tags. This value will NOT be html-encoded. (<tag>[option-value]</tag>)</p>
	 * @param boolean $options[/] <p>Providing this index with value true will add a closing tag as well.</p>
	 * @param string $options[!enter] <p>A special javascript event to execute when the enter key is pressed within the element.</p>
	 *
	 * @return string <p>The HTML code for the tag.</p>
	 */
	protected function tag($tag, $options = []) {
		// options
  		$options = array_merge([
			"!enter" => false,
			"@id" => false,
			"/" => false,
			"^" => false,
			"*" => false,
			"br" => false,
  		], $options);

		// event: !enter
		if ($options["!enter"] && $options["@id"]) {
			// submit form on enter
			if ($options["!enter"] === true && \com\ui\helper::$current_form && \com\ui\helper::$current_form->submit_button_id) {
				$submit_button_id = \com\ui\helper::$current_form->submit_button_id;
				$options["!enter"] = "$('#{$submit_button_id}').click();";
			}

			// script
			$js_id = \LiquidedgeApp\Octoapp\app\app\js\js::parse_id($options["@id"]);
			\com\js::add_script("$('#{$js_id}').keypress(function(event) { if (event.which == 13) { event.preventDefault(); {$options["!enter"]}; }});");
			unset($options["!enter"]);
		}

		// add attributes, styles, classes and events based on index indicators
		$styles = "";
		$class_arr = [];
		$attributes = "";
		foreach ($options as $option_index => $option_item) {
			$indicator = substr($option_index, 0, 1);
			$index = substr($option_index, 1);
			switch ($indicator) {
				case "!" : if ($option_item !== false) $attributes .= " on{$index}=\"{$option_item}\""; break;
				case "#" : if ($option_item !== false) $styles .= "{$index}:{$option_item};"; break;
				case "." : if ($option_item !== false) $class_arr[$index] = $index; break;

				case "@" :
					if (is_array($option_item)) {
						if ($option_item) {
							$attributes .= " {$index}=\"".implode(" ", $option_item).'"';
						}
					}
					else {
						if ($option_item !== false) {
							$attributes .= " {$index}=\"";
							if ($option_item === true) $attributes .= "{$index}\"";
							else $attributes .= "{$option_item}\"";
						}
					}
					break;
			}
		}

		// style
		if ($styles) $attributes .= ' style="'.$styles.'"';

		// class
		if ($class_arr) $attributes .= ' class="'.implode(" ", $class_arr).'"';

		// content
		$content = false;
		if ($options["^"] !== false || $options["*"] !== false) {
			$content = $this->content(($options["^"] !== false ? $options["^"] : $options["*"]), [
				"br" => $options["br"],
				"html" => ($options["*"] ? true : false),
			]);
		}

		// build tag with content
		$html = "<{$tag}{$attributes}";

		// short end tag
		if ($options["/"] && !$content && in_array($tag, ["br", "input", "meta", "link", "img"])) $html .= " />";
		else {
			// content close tag
			$html .= ">{$content}";
			if ($options["/"]) $html .= $this->close($tag);
		}

		// tooltips
		if (!empty($options["@title"])) {
			\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tooltip();
		}

		// done
		return $html;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Build an HTML closing tag.
	 *
	 * @param string $tag <p>The tag that should be closed.</p>
	 *
	 * @return string <p>The HTML for the closing tag.</p>
	 */
	protected function close($tag) {
		// add an end line for certain tags
		$endline = false;
		if (in_array($tag, ["br", "tr"])) $endline = "\n";

		// close tag
		return "</{$tag}>{$endline}";
	}
	//--------------------------------------------------------------------------------
	/**
	 * Parses the special pattern syntax into a usable options array. The following
	 * syntax is available. All syntax options should be seperated by a single space.
	 * When using the ^ or * syntax, any subsequent ^ will also be included in the text
	 * content. The first occurance of either ^ or * will be used.
	 *
	 * <p>Providing a # followed by text, will use the text as the id attribute.</p>
	 * <p>Providing a . followed by text, will use the text as a class.</p>
	 * <p>Providing a / at the end of the pattern, will close the tag provided.</p>
	 * <p>Providing a ^ followed by text, will use the text as html-encoded content within the opening and closing tags.</p>
	 * <p>Providing a * followed by text, will use the text as raw html content within the opening and closing tags.</p>
	 *
	 * @param string $pattern <p>The special syntax string that this function will recognize.</p>
	 *
	 * @return array <p>The parsed options.</p>
	 */
	protected function parse_pattern($pattern) {
		// options
		$options = [];

		// no pattern
		if (!$pattern) return $options;

		// content
		$content_type = "^";
		$content_start = strpos($pattern, "^");
		$star_content_start = strpos($pattern, "*");

		if (false === $content_start || (false !== $star_content_start && $star_content_start < $content_start)) {
			$content_start = $star_content_start;
			$content_type = "*";
		}
		if (false !== $content_start) {
			if (preg_match("/ \\/$/i", $pattern)) $options["/"] = true;
			$options[$content_type] = preg_replace("/ \\/$/i", "", substr($pattern, $content_start + 1));
			$pattern = substr($pattern, 0, $content_start);
		}

		// use spaces as delimiter
		$pattern_arr = explode(" ", $pattern);

		// extract options
		foreach ($pattern_arr as $pattern_item) {
			$signature = substr($pattern_item, 0, 1);
			$value = substr($pattern_item, 1);
			switch ($signature) {
				case "#" : $options["@id"] = $value; break;
				case "." : $options[".{$value}"] = true; break;
				case "/" : $options["/"] = true; break;
			}
		}

		// done
		return $options;
	}
	//--------------------------------------------------------------------------------
}