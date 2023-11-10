<?php

namespace action\dropzone;

/**
 * @package action\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xstream implements \com\router\int\action {

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
    protected function __construct($options = []) {
		\core::$app->set_section(\acc\core\section\website::make());
	}
	//--------------------------------------------------------------------------------
	public function run() {

        $this->session_id = $this->request->get('session_id', \com\data::TYPE_STRING, ["get" => true]);
        $this->index = $this->request->get('index', \com\data::TYPE_STRING, ["get" => true]);

        //stream from session
        $this->stream_file_from_session();

        return "stream";

	}
	//--------------------------------------------------------------------------------
    public function stream_file_from_session() {

		$dropzone_session =  \app\inc\dropzone\session::make(["id" => $this->session_id]);
		$item = $dropzone_session->get_uploaded_file($this->index);
		$filename = isset($item["original"]) ? $item["original"] : false;

	    if(file_exists($filename)){
	        $info = pathinfo($filename);
            if($info && in_array($info["extension"], ["png", "jpg", "jpeg", "gif"])){
                \app\http::stream_file($filename, ["filename" => basename($filename), "download" => false]);
            }else{
                switch ($info["extension"]){
                    case "pdf": $filename = \core::$folders->get_root_files()."/standard/placeholder_pdf.png"; break;
                    case "txt": $filename = \core::$folders->get_root_files()."/standard/placeholder_text.png"; break;
                    default: $filename = \core::$folders->get_root_files()."/standard/placeholder_document.png"; break;
                }
                \app\http::stream_file($filename, ["filename" => basename($filename), "download" => false]);
            }
        }
    }
	//--------------------------------------------------------------------------------
}