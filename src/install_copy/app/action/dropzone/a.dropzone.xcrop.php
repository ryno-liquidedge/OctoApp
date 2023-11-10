<?php

namespace action\dropzone;

/**
 * Class vmanage
 * @package action\system\setup
 * @author Ryno Van Zyl
 */

class xcrop implements \com\router\int\action {

	/**
	 * @var  \app\inc\dropzone\session
	 */
	protected $dropzone_session;

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

    	$session_id = $this->request->get('session_id', \com\data::TYPE_STRING, ["trusted" => true]);
    	$index = $this->request->get('index', \com\data::TYPE_STRING, ["trusted" => true]);
		if(!$session_id){
			\com\error::create("Invalid id");
			\com\http::json([ "code" => 3, "message" => "Access denied",]);
			return "stream";
		}


		//init session
		$this->dropzone_session =  \app\inc\dropzone\session::make(["id" => $session_id]);

		//params
		$filename = $this->dropzone_session->get_uploaded_file($index, "original");

		$result =  \app\inc\dropzone\crop_helper::make()->xcrop($filename, ["index" => $index]);

		$this->dropzone_session->add_uploaded_file($result["original"], $result["cropped"], ["index" => $index]);
		$this->dropzone_session->update();

		return \app\http::ajax_response($this->dropzone_session->uploaded_files_arr);

    }
    //--------------------------------------------------------------------------------
}

