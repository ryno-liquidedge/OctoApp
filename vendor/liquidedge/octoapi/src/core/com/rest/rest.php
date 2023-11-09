<?php

namespace octoapi\core\com\rest;


class rest extends \octoapi\core\com\intf\standard {
	//--------------------------------------------------------------------------------
	public static function call($method, $url, $data = false, $options = []) {
		$options = array_merge([
			">CURLOPT_CUSTOMREQUEST" => strtoupper($method),
			">CURLOPT_POSTFIELDS" => ($data !== false ? json_encode($data) : false),
			">CURLOPT_HTTPHEADER" => [
				"Content-Type: application/json",
			],
			">CURLOPT_USERAGENT" => (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : false),

			"username" => false,
			"password" => false,
			"download" => false,
		], $options);

		$result = self::curl($url, $options);

		return [
			"body" => ($options["download"] ? $result["body"] :  json_decode($result["body"], true)),
			"headers" => $result["headers"],
			"cookies" => $result["cookies"],
			"info" => $result["info"],
			"error" => $result["error"],
			"error_nr" => $result["error_nr"],
		];
	}
	//--------------------------------------------------------------------------------
	public static function curl($url, $options = []) {
		// options
		$options = array_merge([
			">CURLOPT_RETURNTRANSFER" => 1,
			">CURLOPT_FOLLOWLOCATION" => 1,
			">CURLOPT_SSL_VERIFYPEER" => 0,
			">CURLOPT_HEADER" => 1,
			">CURLINFO_HEADER_OUT" => 1,
			">CURLOPT_CONNECTTIMEOUT" => 10,
			">CURLOPT_TIMEOUT" => 30,

			"username" => false,
			"password" => false,
		], $options);

		// username and password
		if ($options["username"]) $options[">CURLOPT_USERPWD"] = "{$options["username"]}:{$options["password"]}";

		// init
  		$curl = curl_init($url);

		// set options
		foreach ($options as $option_index => $option_item) {
			// skip non-sub options
			if (substr($option_index, 0, 1) != ">") continue;

			// set curl option
			curl_setopt($curl, constant(substr($option_index, 1)), $option_item);
		}

  		// process and get info
		$result_arr = [
			"header" => false,
			"body" => false,
			"info" => false,
			"error" => false,
			"error_nr" => false,
			"cookies" => [],
		];

  		$response = curl_exec($curl);


  		$result_arr["info"] = curl_getinfo($curl);
		$result_arr["headers"] = self::http_parse_headers(substr($response, 0, $result_arr["info"]["header_size"]));
		$result_arr["body"] = substr($response, $result_arr["info"]["header_size"]);
		if (isset($result_arr["headers"]["Set-Cookie"])) $result_arr["cookies"] = self::parse_cookies($result_arr["headers"]["Set-Cookie"]);
		if ($response === false) {
		    $result_arr["error"] = curl_error($curl)." (".curl_errno($curl).")";
		    $result_arr["error_nr"] = curl_errno($curl);
		}

  		// close
  		curl_close($curl);

		// done
  		return $result_arr;
	}
	//--------------------------------------------------------------------------------
	public static function http_parse_headers($raw_headers) {
		$headers = array();
        $key = '';

        foreach(explode("\n", $raw_headers) as $i => $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]]))
                    $headers[$h[0]] = trim($h[1]);
                elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                }
                else {
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                }

                $key = $h[0];
            }
            else {
                if (substr($h[0], 0, 1) == "\t")
                    $headers[$key] .= "\r\n\t".trim($h[0]);
                elseif (!$key)
                    $headers[0] = trim($h[0]);
            }
        }

        return $headers;
    }
    //--------------------------------------------------------------------------------
	public static function parse_cookies($cookie_arr) {
		$cookie_arr = \octoapi\core\com\arr\arr::splat($cookie_arr);
		$parsed_cookie_arr = [];
		foreach ($cookie_arr as $cookie_item) {
			// do we have a value
			$cookie_item = trim($cookie_item);
			if (!$cookie_item) continue;

			// split into cookie params
			$key = false;
			$cookie = [
				"value" => false,
				"domain" => false,
				"expires" => false,
				"path" => false,
				"secure" => false,
				"comment" => false,
			];
			$split_arr = explode(";", $cookie_item);
			foreach ($split_arr as $split_item) {
				// split item into key value pair
				$split_item = trim($split_item);
				$key_value = explode("=", $split_item);

				//fill
				$key_value[0] = trim($key_value[0]);
				$key_value[1] = (isset($key_value[1]) ? trim($key_value[1]) : false);
				if (in_array($key_value[0], ["domain", "expires", "path", "secure", "comment"])) {
					$cookie[$key_value[0]] = $key_value[1];
				}
				else {
					$key = $key_value[0];
					$cookie["value"] = $key_value[1];
				}
			}

			// add cookie
			if ($key) $parsed_cookie_arr[$key] = $cookie;
		}

		// done
		return $parsed_cookie_arr;
	}
	//--------------------------------------------------------------------------------
}