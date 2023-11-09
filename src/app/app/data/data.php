<?php

namespace LiquidedgeApp\Octoapp\app\app\data;

/**
 * Class data
 * @package app
 * @author Ryno Van Zyl
 */

class data extends \com\data{

    //--------------------------------------------------------------------------------
    public static function dbvalue($value, $wrap_quotes = true) {
        $quote = $wrap_quotes ? "'" : "";
        $replace_arr = ["'" => "''", '<' => '&lt;', "\0" => ""];
        return ($value === 'null' ? 'NULL' : "$quote".strtr(iconv(\core::$app->get_instance()->get_charset(), \core::$app->get_instance()->get_db_charset().'//TRANSLIT//IGNORE', $value), $replace_arr)."$quote");
    }
    //--------------------------------------------------------------------------------
    public static function is_html($string, $options = []) {

        $string = html_entity_decode($string);
        if(strlen($string) !== strlen(strip_tags($string))) return true;
        return false;
    }
    //--------------------------------------------------------------------------------
	public static function parse_paragraph($text) {
		$text = \LiquidedgeApp\Octoapp\app\app\str\str::br2nl($text);
		$text = nl2br(strip_tags($text));
		return preg_replace("/(<br\s*\/>\s*)+/", "<br /><br />", $text);
	}
    //--------------------------------------------------------------------------------
	public static function parse_url($url, $options = []) {

    	$options = array_merge([
    	    "protocol" => "https"
    	], $options);

    	if(substr($url, 0, 4) !== "http"){
    		$url = "{$options["protocol"]}://{$url}";
		}

    	return $url;
	}
    //--------------------------------------------------------------------------------
	public static function count_newlines($text) {
		$text = \LiquidedgeApp\Octoapp\app\app\str\str::br2nl($text);
		return substr_count( $text, "\n" );
	}
    //--------------------------------------------------------------------------------
	public static function limit_paragraph_by_line_count($text, $options = []) {

    	$options = array_merge([
    	    "total_lines" => 5,
    	    "line_length" => 120,
    	    "append" => "...",
    	    "allow_newlines" => true,
    	], $options);

		$return_arr = [];
		$text = \LiquidedgeApp\Octoapp\app\app\data\data::parse_paragraph($text);
		$text_arr = explode("\n", $text);
		$has_exceeded = false;

		//apply line length limit count
		array_walk($text_arr, function($line, $index)use(&$return_arr, &$has_exceeded, $options){

			if(sizeof($return_arr) > $options["total_lines"]) return $has_exceeded = true;

			if(isempty($line) && $options["allow_newlines"]){
				$return_arr[] = "\n";
			}else{
				$text_arr = str_split($line, $options["line_length"]);
				foreach ($text_arr as $text){
					if(sizeof($return_arr) > $options["total_lines"]) return $has_exceeded = true;
					$return_arr[] = trim($text);
				}
			}
		});

		if($has_exceeded && strpos($options["append"], "...") !== false){
			$last_index = \LiquidedgeApp\Octoapp\app\app\data\arr::get_last_index($return_arr);
			$last_line = $return_arr[$last_index];

			if(substr($last_line, strlen($last_line) - 1, 1) == "."){
				$last_line = substr($last_line, 0, strlen($last_line)-1);
			}
			$return_arr[$last_index] = $last_line.$options["append"];
		}else if($has_exceeded && $options["append"]){
			$return_arr[] = $options["append"];
		}

		$text = \LiquidedgeApp\Octoapp\app\app\data\data::parse_paragraph(implode(" ", $return_arr));
		if(!$options["allow_newlines"]) $text = strip_tags($text);

		return $text;
	}
    //--------------------------------------------------------------------------------
	public static function is_valid_email($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

			if(!preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/", $email)){
				return false;
			}

			if(\core::$app->get_instance()->get_environment() == "LIVE"){
			    $email_arr = explode("@", $email);
                if (!checkdnsrr(array_pop($email_arr), "MX")) {
                    return false;
                }
            }
		}

		return true;
	}
    //--------------------------------------------------------------------------------

	/**
	 * multi functional check to see if a value is empty
	 * @param $mixed
	 * @param false $datatype
	 * @param array $options
	 * @return bool
	 */
	public static function is_empty($mixed, $datatype = false, $options = []){

		// check if is text
		switch ($datatype) {
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_DATE:
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_DATETIME:
				if(isnull($mixed)) return true;
				return false;

			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_INT:
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_FLOAT:
				if(\LiquidedgeApp\Octoapp\app\app\data\data::parse_float($mixed) == 0) return true;
				return false;

			default:
				// check basics
				if(isnull($mixed) || is_null($mixed) || $mixed === false || $mixed === "" || strtolower($mixed) === "null") return true;

				// check if is array
				if(is_array($mixed)) if(count($mixed) == 0) return true;
		}
	}
    //--------------------------------------------------------------------------------
    public static function sanitize_html($buffer) {

        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );

        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }
    //--------------------------------------------------------------------------------
}
