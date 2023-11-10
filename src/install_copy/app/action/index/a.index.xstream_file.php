<?php

namespace action\index;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xstream_file implements \com\router\int\action {
	//--------------------------------------------------------------------------------
    // properties
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
    protected function __construct($options = []) {
		\core::$app->set_section(\acc\core\section\no_audit::make());
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
    public function run() {

        $id = $this->request->get("id", \com\data::TYPE_STRING, ["get" => true]);
        $download = $this->request->get('download', \com\data::TYPE_BOOL, ["trusted" => true]);
        $filename = $this->request->get("filename", \com\data::TYPE_STRING, ["get" => true]);
        $is_file_item = $this->request->get("is_file_item", \com\data::TYPE_STRING, ["get" => true]);

    	try{
    	    $id = urldecode($id);
			$mixed = base64_decode($id);
			if($is_file_item){
			    $mixed = \com\data::parse_reference($mixed);
			    $mixed = \core::dbt("file_item")->get_fromdb($mixed);
            }

			if ($mixed && is_string($mixed) && file_exists($mixed)) {
			    if(!$filename) $filename = basename($mixed);
			    \app\http::add_stream_headers($filename, ["download" => false]);
				\app\http::stream_file($mixed, ["download" => false, "filename" => $filename,]);
			}elseif($mixed && $mixed instanceof \com\db\row){
				try{
				    if(!$filename) $filename = $mixed->fil_filename;
					$manager = \app\file\manager\file_item::make(["file_item" => $mixed]);
					\app\http::add_stream_headers($filename, ["download" => false]);
					$manager->stream(["download" => $download]);
				}catch(\Exception $ex){}
			}else{
				\app\http::stream_file(\core::$folders->get_root_files() . "/standard/placeholder-maxarea_300x200.jpg", ["filename" => "placeholder-maxarea_300x200.jpg", "download" => $download]);
			}
    	}catch(\Exception $ex){}

        return "stream";
    }
	//--------------------------------------------------------------------------------
}