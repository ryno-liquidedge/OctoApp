<?php

namespace LiquidedgeApp\Octoapp\app\app\cron;

/**
 * @package app\cron\cron
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class maintenance extends \LiquidedgeApp\Octoapp\app\app\cron\intf\standard {

	//--------------------------------------------------------------------------------
	function on_run($options = []) {

		//cleanup temp folder
        $this->clean_directory(\core::$folders->get_temp()."/");

        //clean session
        $this->clean_sessions();

        //compress images
        $this->compress_public_images();

	}
    //--------------------------------------------------------------------------------
    public function clean_sessions() {
        $clean_sessions = \LiquidedgeApp\Octoapp\app\app\email\maint\task\clean_sessions::make();
		$clean_sessions->run();

		\core::db()->query("DELETE FROM session WHERE ses_url = '' AND DATE(ses_date_added) < '".\LiquidedgeApp\Octoapp\app\app\date\date::strtodate("today - 1 day")."' LIMIT 100000;");
    }
    //--------------------------------------------------------------------------------
    public function clean_directory($path) {

        foreach (new \DirectoryIterator($path) as $fileinfo){
            if($fileinfo->isDot()) continue;

            if($fileinfo->isDir()) {
                $this->clean_directory($fileinfo->getPathname());
            } else if($fileinfo->isFile()){
                $last_modification_datetime = date(\core::$app->get_instance()->get_format_datetime(), $fileinfo->getMTime());
                if(\LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime($last_modification_datetime) < \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime("now - 1 day")){
                    @unlink($fileinfo->getPathname());
                }
            }

            if(\LiquidedgeApp\Octoapp\app\app\os\os::sizeofdir($fileinfo->getPathname()) === 0)
                \LiquidedgeApp\Octoapp\app\app\os\os::removedir($fileinfo->getPathname());

        }

    }
    //--------------------------------------------------------------------------------
    public function compress_public_images() {

	    $slider_arr = \core::dbt("slider")->get_fromdb("sli_is_active = 1", ["multiple" => true]);
	    foreach ($slider_arr as $slider){
            $slider->file_item->tinify();
        }

    }
    //--------------------------------------------------------------------------------
}
