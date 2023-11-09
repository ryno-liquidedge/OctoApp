<?php

namespace LiquidedgeApp\Octoapp\app\app\asset;

/**
 * Handles assets such as javascript, stylesheets and images. Can stream these to the
 * client browser. Also handles packing of the objects to send as one file instead of
 * many smaller ones.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class asset extends \com\asset {
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * Stream a file to the browser. Checked for browser caching and reacts accordingly.
	 * Will generate an error and return http code 404 if the file was not found.
	 *
	 * @param string $filepath <p>The full path of the file to stream.</p>
	 *
	 * @return boolean <p>Whether or not a file was streamed.</p>
	 */
	protected static function stream_file($filepath) {
		// check if the file exists
		if (!$filepath || !file_exists($filepath)) {
			return \LiquidedgeApp\Octoapp\app\app\error\error::create("File not found: {$filepath}", ["http_code" => 404]);
		}

		// check for supported file extension
		$pathinfo = pathinfo($filepath);
		if (!isset(self::$registered_file_arr[$pathinfo["extension"]])) {
			return \LiquidedgeApp\Octoapp\app\app\error\error::create("Invalid file extension provided: {$filepath}", ["http_code" => 404]);
		}

		// close session to allow more requests while content is processed
		if (\com\session::$current && \core::$app->get_instance()->get_db_enabled()) {
			\com\session::$current->close();
		}

		// init
		$filemtime = filemtime($filepath);
		$md5_file = md5_file($filepath);

		// headers and content type
		header("Etag: \"{$md5_file}\"");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $filemtime)."GMT");
	  	header("Content-type: ".self::$registered_file_arr[$pathinfo["extension"]]);

	  	ini_set("default_mimetype", self::$registered_file_arr[$pathinfo["extension"]]);
  		ini_set("default_charset", "");

		// check file id
		if (isset($_SERVER["HTTP_IF_NONE_MATCH"]) && preg_match("/{$md5_file}/i", $_SERVER["HTTP_IF_NONE_MATCH"])) {
	    	http_response_code(304);
	    	return false;
	  	}

	  	// check last modified
	  	if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) && (strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) == $filemtime)) {
	    	http_response_code(304);
	    	return false;
	  	}

	  	// stream file
		\LiquidedgeApp\Octoapp\app\app\asset\http::add_stream_headers($pathinfo["basename"], ["download" => false,]);
	  	header("Content-Disposition: attachment; filename={$pathinfo["basename"]}");
	  	readfile($filepath);

		// done
		return true;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Streams a file to the client. Packs files as needed before streaming.
	 *
	 * @param string $filename <p>Just the file name to stream. Packed files can be streamed via: css, cssprint or js.</p>
	 * @param string $group <p>The group that the file belongs to when streaming images. A folder registered in $group_arr.</p>
	 * @param type $themed <p>If true, then the theme specified in the setup folder will be used as an offset folder.</p>
	 */
	public static function stream($filename, $group = false, $themed = false) {
		// make sure our name does not include unwanted chars
		$filename = \LiquidedgeApp\Octoapp\app\app\data\data::parse_file($filename);

		// packed asset files
		switch ($filename) {
			case "css" :
				$filepath = \com\asset::pack(self::$folder."/assets.min.css", self::$css_arr, ["only_if_newer" => true, "only_if_auth" => "dev"]);
				return self::stream_file($filepath);
				break;

			case "cssprint" :
				$filepath = \com\asset::pack(self::$folder."/assets.print.min.css", self::$css_print_arr, ["only_if_newer" => true, "only_if_auth" => "dev"]);
				return self::stream_file($filepath);
				break;

			case "js" :
				$filepath = \com\asset::pack(self::$folder."/assets.min.js", self::$js_arr, ["only_if_newer" => true, "only_if_auth" => "dev"]);
				return self::stream_file($filepath);
				break;
		}

		// check if we have the image group registered
		if (!$group) $group = "app";
		if (!isset(self::$group_arr[$group])) {
			return \LiquidedgeApp\Octoapp\app\app\error\error::create("The specified group does not exist: {$group}", ["http_code" => 404]);
		}

		// build file path
		$filepath = false;
		$pathinfo = pathinfo($filename);
		switch ($pathinfo["extension"]) {
			case "jpg" :
			case "png" :
			case "ico" :
			case "gif" :
			case "svg" :
				$filepath = self::$group_arr[$group];
				if ($themed) $filepath .= "/charcoal_gold";
				$filepath .= "/{$filename}";
				break;
		}

		// stream file
		self::stream_file($filepath);
	}
	//--------------------------------------------------------------------------------
}