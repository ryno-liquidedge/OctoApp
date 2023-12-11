<?php

namespace action\dropzone;

/**
 * Class vmanage
 * @package action\system\setup
 * @author Ryno Van Zyl
 */

class xupload implements \com\router\int\action {

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

    	$session_id = $this->request->get('session_id', \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
    	$index = $this->request->get('index', \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING);
		if(!isset($_FILES["file"]["name"]))
			return \com\error::create("Error uploading file");

		if(!$session_id){
			\com\error::create("Invalid id");
			\com\http::json([ "code" => 3, "message" => "Access denied",]);
			return "stream";
		}

		//init session
		$this->dropzone_session =  \LiquidedgeApp\Octoapp\app\app\inc\dropzone\session::make(["id" => $session_id]);

		//build file info
		$pathinfo = pathinfo(strtolower($_FILES["file"]["name"]));
		$pathinfo["filename"] = \LiquidedgeApp\Octoapp\app\app\data\data::parse_file($pathinfo["filename"]);
		$pathinfo["basename"] = \LiquidedgeApp\Octoapp\app\app\data\data::parse_file($pathinfo["basename"]);

		// check that folder exists
		\com\os::mkdir($this->dropzone_session->folder);

		//check that the extension is allowed
		$filetype_group = \app\os\filetype_group::make($this->dropzone_session->filetype_group);
		if (isset($pathinfo["extension"])) $pathinfo["extension"] = strtolower($pathinfo["extension"]);
		if (!isset($pathinfo["extension"]) || !$filetype_group->has_extension($pathinfo["extension"])) {
			\com\error::create("File extension not allowed: {$pathinfo["extension"]}");
			\com\http::json([ "code" => 1, "message" => "Extension not allowed",]);
			return "stream";
		}

		// move uploaded file
		try {
			$upload_path = \com\os::to_filepath("{$this->dropzone_session->folder}/{$pathinfo["basename"]}");
		} catch (\com\error\exception\generic $e) {
			\com\error::create($e);
			\com\http::json([ "code" => 3, "message" => "Could not upload file",]);
			return "stream";
		}

		//attempt to append name if file already exists
		$i = 1;
		while (file_exists($upload_path)) {
			$upload_path = \com\os::to_filepath("{$this->dropzone_session->folder}/{$pathinfo["filename"]}($i).{$pathinfo["extension"]}");
			$i++;
		}

		//move the uploaded file
		move_uploaded_file($_FILES["file"]["tmp_name"], $upload_path);

		\app\http::json(["uploaded_file" => basename($upload_path)]);

		$this->dropzone_session->add_uploaded_file($upload_path, false, ["index" => $index]);
		$this->dropzone_session->update();

		return "stream";
    }
    //--------------------------------------------------------------------------------
}

