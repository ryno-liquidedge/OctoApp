<?php

namespace action\dropzone;

/**
 * Class vmanage
 * @package action\system\setup
 * @author Ryno Van Zyl
 */

class xdelete implements \com\router\int\action {

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

    	$session_id = $this->request->get('session_id', \com\data::TYPE_STRING);
    	$index = $this->request->get('index', \com\data::TYPE_STRING);

		if(!$session_id){
			\com\error::create("Invalid id");
			\com\http::json([ "code" => 3, "message" => "Access denied",]);
			return "stream";
		}

		//init session
		$this->dropzone_session =  \app\inc\dropzone\session::make(["id" => $session_id]);
		$this->dropzone_session->remove_uploaded_file($index);
		$this->dropzone_session->update();

    }
    //--------------------------------------------------------------------------------
}

