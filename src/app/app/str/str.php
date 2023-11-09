<?php

namespace LiquidedgeApp\Octoapp\app\app\str;

/**
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class str extends \com\str{
	//--------------------------------------------------------------------------------
	// static functions
	//--------------------------------------------------------------------------------
    public static function get_random_id($prepend = false, $options = []){
        $options = array_merge([
            "separator" => "_",
            "append" => "_",
            "lowercase" => true,
        ], $options);

    	$parts = [];

    	if($prepend){
    	    $prepend_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($prepend);
    	    foreach ($prepend_arr as $prepend){
    	        if($options["lowercase"]) $prepend = strtolower($prepend);
                $parts[] = $prepend;
            }
        }
        $parts[] = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_alpha(5, ["lowercase" => true,]);

    	if($options["append"]){
    	    $append_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["append"]);
    	    foreach ($append_arr as $append){
    	        if($options["lowercase"]) $append = strtolower($append);
                $parts[] = $append;
            }
        }

        return implode($options["separator"], $parts);
    }
    //--------------------------------------------------------------------------------
	/**
	 * function returns the amount of words specified in a String
	 * @param $string
	 * @param int $word_count
	 * @return string
	 */
    public static function limit_string_word_count($string, $word_count = 5){
        $word_arr = explode(' ', $string);
        $word_slice = array_slice($word_arr, 0, $word_count);
        if(count($word_arr) > $word_count){
            return implode(' ', $word_slice)."...";
        }
        return implode(' ', $word_slice);
    }
    //--------------------------------------------------------------------------------
    public static function str_to_word_arr($str, $options = []) {
        $options = array_merge([
            "separator" => " "
        ], $options);

        $title_parts = explode($options["separator"], $str);

        foreach ($title_parts as $index => $value){
            $title_parts[$index] = trim($value);
        }

        return $title_parts;
    }
    //--------------------------------------------------------------------------------
    public static function has_alpha_chars($text) {
        return preg_match("/[a-z]/i", $text);
    }
    //--------------------------------------------------------------------------------
    /**
     * function appends a number suffix via thi applicable number passed in parms
     * @param int $number
     * @return string value with suffixed nummber
     */
    public static function append_number_suffix($number) {
        $suffix = array('th','st','nd','rd','th','th','th','th','th','th');
        if (!$number){
            return false;
        }else if((($number % 100) >= 11) && (($number%100) <= 13)){
            return $number. 'th';
        }else{
            return $number. $suffix[$number % 10];
        }
    }
    //--------------------------------------------------------------------------------
    public static function format_link($string, $options = []) {

        $options_arr = array_merge([
            "default" => false,
            "type" => "link", //link / email
            "return_tag" => false,
        ], $options);

        $return = $options_arr['default'];

        if($options_arr["type"] == "link"){
            if($string == "")return $return;

            if(strpos($string, "http://") === false){
                if(strpos($string, "https://") !== false){
                    $return = $string;
                }else{
                    $return = "http://".$string;
                }
            }else if(strpos($string, "http://") !== false){
                $return = $string;
            }

            if($return && $options_arr["return_tag"]){
                return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->a("^". \LiquidedgeApp\Octoapp\app\app\str\str::strip_http($string), ["@target" => "__blank", "@href" => "$string"]);
            }
        }else if($options_arr["type"] == "email"){
            $email_data = [];
            if(isset($options_arr["email_subject"])){
                $email_data["subject"] = "subject={$options_arr["email_subject"]}";
            }
            if(isset($options_arr["email_body"])){
                $email_data["body"] = "body={$options_arr["email_body"]}";
            }

            $email_data_str = $email_data ? implode("&", $email_data) : "";

            return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->a("^". \LiquidedgeApp\Octoapp\app\app\str\str::strip_http($string), ["@href" => "mailto:$string?$email_data_str"]);
        }


        return $return;
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $string
	 * @param bool $return_default
	 * @return bool|mixed
	 */
    public static function strip_http($string, $return_default = false) {
        if($string == "")return $return_default;
        return str_replace(["https://", "http://"], "", $string);
    }
    //--------------------------------------------------------------------------------

    /**
     * Generates random hex string
     * @param int $num_bytes
     * @return string
     */
    public static function get_random_hex($num_bytes = 4) {
        return bin2hex(openssl_random_pseudo_bytes($num_bytes));
    }
    //--------------------------------------------------------------------------------

	/**
	 * limits a string via the string length and not the word count.
	 * This function does however return full words with an elipses if the string length exceeds the specified parm amount
	 * @param $string
	 * @param int $length
	 * @param string $default
	 * @param array $options
	 * @return mixed|string
	 */
    public static function limit_string_by_length($string, $length = 25, $default = "", $options = []) {

    	$options = array_merge([
    	    "append" => "..."
    	], $options);

        if(!$string || $string == '' || $string == 'null'){
            return $default;
        }
        if(strlen($string) < $length){
            return $string;
        }
        $return = substr($string, 0, $length);
        return $return.$options["append"];
    }
	//--------------------------------------------------------------------------------
    public static function strip_special_chars($string) {

    	$string = \LiquidedgeApp\Octoapp\app\app\str\str::strip_accents($string);

        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
            '/[“”«»„]/u'    =>   ' ', // Double quote
            '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $string);
    }
    //--------------------------------------------------------------------------------
	public static function strip_alpha($value, $options = []){
		$options_arr = array_merge([
			"format" => false,
			"default" => false
		], $options);

		switch ($options_arr["format"]) {
			case DB_CURRENCY: $return = preg_replace("/[^0-9.]/", "", $value);
			case DB_DECIMAL: $return = preg_replace("/[^0-9.]/", "", $value); break;
			case DB_INT: $return = preg_replace("/[^0-9]/", "", $value); break;
			default: $return = $options_arr["default"]; break;
		}

        return $return;
	}
	//--------------------------------------------------------------------------------
    public static function br2nl($str) {
        return preg_replace('#<br\s*/?>#i', "\n\n", $str);
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $html
	 * @return mixed
	 */
    public static function extract_src_from_iframe($html) {
        $doc = new \DOMDocument();
		$doc->loadHTML($html);
		$xpath = new \DOMXPath($doc);
		$query = "//iframe";
		$entries = $xpath->query($query);
		foreach ($entries as $entry) {
		  return $entry->getAttribute("src");
		}
    }
    //--------------------------------------------------------------------------------
    public static function sanitize_html($str) {

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

        $buffer = preg_replace($search, $replace, $str);

        return $buffer;
    }
    //--------------------------------------------------------------------------------
    public static function str_to_seo($string, $word_limit = 0, $options = []) {
	    $options_arr = array_merge([
	        "utf_enabled" => true,
	        "separator" => '-',
	    ], $options);

        $separator = $options_arr["separator"];

        if ($word_limit != 0) {
            $wordArr = explode(' ', $string);
            $string = implode(' ', array_slice($wordArr, 0, $word_limit));
        }

        $string = self::strip_tags($string, $options_arr);
        $string = self::str_clean($string);

        return strtolower(trim(trim($string, $separator)));
    }
    //--------------------------------------------------------------------------
    public static function strip_tags($string, $options = []) {
	    $options_arr = array_merge([
	        "utf_enabled" => true,
	        "separator" => '-',
	    ], $options);

	    $separator = $options_arr["separator"];

	    $quoteSeparator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;' => '',
            '[^\w\d _-]' => '',
            '\s+' => $separator,
            '(' . $quoteSeparator . ')+' => $separator
        );

        $string = strip_tags($string);
        foreach ($trans as $key => $val) {
            $string = preg_replace('#' . $key . '#i' . ($options_arr["utf_enabled"] ? 'u' : ''), $val, $string);
        }

        return $string;
    }
    //--------------------------------------------------------------------------
	public static function session_name($name) {
		return str_replace([" ", "/", "."], "", \LiquidedgeApp\Octoapp\app\app\str\str::str_clean($name));
	}
    //--------------------------------------------------------------------------
    public static function str_clean($text) {
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
            '/[“”«»„]/u'    =>   ' ', // Double quote
            '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
	//--------------------------------------------------------------------------------
	public static function extract_between_two_chars($str, $char_start, $char_end = false) {
    	if(!$char_end) $char_end = $char_start;
		$substring_start = strpos($str, $char_start);
		$substring_start += strlen($char_start);
		$size = strpos($str, $char_end, $substring_start) - $substring_start;
		return substr($str, $substring_start, $size);
	}
	//--------------------------------------------------------------------------------
    public static function generate_guidv4 ($data = false){
        if (!$data) {
            $data = \openssl_random_pseudo_bytes(16);
        }

        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
	//--------------------------------------------------------------------------------
}