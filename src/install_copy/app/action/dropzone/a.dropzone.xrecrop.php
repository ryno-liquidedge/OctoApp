<?php

namespace action\dropzone;

/**
 * Class vmanage
 * @package action\system\setup
 * @author Ryno Van Zyl
 */

class xrecrop implements \com\router\int\action {

	/**
	 * @var  \app\inc\dropzone\session
	 */
	protected $dropzone_session;

	/**
	 * @var \db_file_item
	 */
	protected $file_item;

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

    	$this->file_item = $this->request->getdb("file_item", "fil_id");

    	if(!$this->file_item){
    		return \com\error::create("db_file_item not found");
		}

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

		$manager = \app\file\manager\file_item::make(["file_item" => $this->file_item]);
		$manager->save_from_file($result["cropped"]);

		\com\os::removedir($this->dropzone_session->folder);
		$this->dropzone_session->clear();


		return \app\http::ajax_response($this->dropzone_session->uploaded_files_arr);

    }
    //--------------------------------------------------------------------------------
}

