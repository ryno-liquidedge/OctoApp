<?php

namespace action\dropzone;

/**
 * Class vmanage
 * @package action\system\setup
 * @author Ryno Van Zyl
 */

class crop implements \com\router\int\action {

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

		if(!$session_id){
			\com\error::create("Invalid id");
			\com\http::json([ "code" => 3, "message" => "Access denied",]);
			return "stream";
		}

		//init session
		$this->dropzone_session =  \app\inc\dropzone\session::make(["id" => $session_id]);

		//params
        $id = $this->dropzone_session->dropzone_id;
		$folder = $this->dropzone_session->folder;
    	$index = $this->request->get('index', \com\data::TYPE_STRING, ["trusted" => true]);
		$cropper_width = $this->request->get("width", \com\data::TYPE_INT, ["get" => true, "default" => $this->dropzone_session->crop_width]);
        $cropper_height = $this->request->get("height", \com\data::TYPE_INT, ["get" => true, "default" => $this->dropzone_session->crop_height]);
		$file = $this->dropzone_session->get_uploaded_file($index, "original");

		if(file_exists($file)){
			$cropper =  \app\inc\dropzone\cropper::make([
			    "!success" => $this->dropzone_session->on_crop,
            ]);

			$url = "/index.php?c=dropzone/xstream&session_id={$session_id}&index={$index}";
			if(!\core::$app->get_instance()->get_option("app.website.enable_url_rewrite") && \core::$app->get_instance()->get_option("url.sub.folder.fix")) {
                $url = str_replace("/index.php?c=", "?c=", $url);
            }
			if(\core::$app->get_instance()->get_option("url.absolute")){
			    $url = \core::$app->get_instance()->get_url().$url;
            }

			$cropper->set_src($url);
			$cropper->set_dest("{$folder}/cropped");
			$cropper->add_data("dropzone_id", $this->dropzone_session->dropzone_id);
			$cropper->add_data("session_id", $session_id);
			$cropper->add_data("filename", basename($file));
			$cropper->add_data("path", $file);
			$cropper->add_data("index", $index);
			$cropper->set_id($index);
			$cropper->set_width($cropper_width);
			$cropper->set_height($cropper_height);

			echo $cropper->build();
		}
    }
    //--------------------------------------------------------------------------------
}

