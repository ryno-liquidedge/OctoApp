<?php

namespace LiquidedgeApp\Octoapp\app\app\cron\helper;

/**
 * @package app\cron\cron
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class helper extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	//--------------------------------------------------------------------------------
    public static function ini_boost($options= []) {

        $options_arr = array_merge([
            "max_execution_time" => 60*60,          // 1h
            "max_input_time" => 60*60,              // 1h
            "post_max_size" => 1073741824,          // 1GB
            "upload_max_filesize" => 1073741824,    // 1GB
            "memory_limit" => 1073741824,           // 1GB
            "wait_timeout" => 288000,
            "max_allowed_packet" => 1073741824,
        ], $options);

        foreach ($options_arr as $key => $value) ini_set($key, $value);

        //php set timeout
        set_time_limit(60*60);
    }
	//--------------------------------------------------------------------------------
}
