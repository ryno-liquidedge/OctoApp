<?php

namespace app;

/**
 * This class contains operating system helper functions such as file management and
 * execution.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class os extends \com\os {
    //--------------------------------------------------------------------------------
	public static function safename($file_to) {
		// init
		$file_to = self::to_filepath($file_to);
 		$pathinfo_to = pathinfo($file_to);
 		
 		// make sure the path exists
		static::mkdir($pathinfo_to["dirname"]);
 		
 		// if we do not have such a file, it is safe
		if (!file_exists($file_to)) {
			return $pathinfo_to["basename"];
		}
		
		// build new name
		$append = \com\str::get_random_alpha(5, ["lowercase" => true]);
		$file_to = self::to_filepath($file_to, ["limit" => -strlen($append)]);
		$pathinfo_to = pathinfo($file_to);

		// done
		return "{$pathinfo_to["filename"]}-{$append}.{$pathinfo_to["extension"]}";
	}
	//--------------------------------------------------------------------------------
    public static function get_linux_cron_command() {

	    $cronfile = \core::$folders->get_app()."/cron/trigger_all.php";
        if(!file_exists($cronfile)) {
            return \com\error::create("Cron File $cronfile is missing.", ["fatal" => true]);
        }

        $ssh_name = str_replace("/usr/www/users/", "", $cronfile);
        $ssh_name_parts = explode("/", $ssh_name);
        $ssh_name = reset($ssh_name_parts);

        return str_replace("/usr/www/users/{$ssh_name}/", "/usr/home/{$ssh_name}/public_html/", "/usr/bin/php-wrapper {$cronfile} >/dev/null 2>/dev/null &");
    }
    //--------------------------------------------------------------------------------
}