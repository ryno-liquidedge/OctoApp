<?php

namespace action\dropzone;

/**
 * Class vmanage
 * @package action\system\setup
 * @author Ryno Van Zyl
 */

class recrop implements \com\router\int\action {

	/**
	 * @var \db_file_item
	 */
	protected $file_item, $source_file_item;

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
		$this->source_file_item = $this->request->getdb("file_item", "source_fil_id", ["get" => true]);
		$this->file_item = $this->request->getdb("file_item", "fil_id", ["get" => true]);

		if(!$this->file_item){
    		return \com\error::create("db_file_item not found");
		}

		if(!$this->source_file_item) $this->source_file_item = $this->file_item;

		$session_id = "recrop_".$this->source_file_item->id;
		$manager = \app\file\manager\file_item::make(["file_item" => $this->source_file_item]);
		$new_filename = \core::$folders->get_temp()."/dropzone/{$session_id}/{$manager->get_filename()}";
		$manager->copy_to($new_filename);


		$session =  \app\inc\dropzone\session::make(["id" => $session_id]);
		$session->dropzone_id = $session_id;
		$session->element_id = $session_id;
		$session->folder = dirname($new_filename);
		$session->filetype_group = \com\os\filetype_group\images::make();
		$session->has_cropper = true;
		$session->crop_width = 250;
		$session->crop_height = 300;
		$index = $session->add_uploaded_file($new_filename, false, ["index" => \app\str::get_random_id("crop")]);
		$session->update();

		// html
		$this->buffer = \app\ui::make()->html_buffer();

		$this->buffer->div_([".row" => true]);
			$this->buffer->div_([".col-12 mt-3" => true]);

				//init session
				$dropzone_session =  \app\inc\dropzone\session::make(["id" => $session_id]);

				$cropper =  \app\inc\dropzone\cropper::make([
					"action" => "?c=dropzone/xrecrop",
				]);
				$cropper->set_src(\app\http::get_stream_url($new_filename));
				$cropper->set_dest(dirname($new_filename)."/cropped");
				$cropper->set_id($index);
				$cropper->add_data("index", $index);
				$cropper->add_data("dropzone_id", $dropzone_session->dropzone_id);
				$cropper->add_data("session_id", $session_id);
				$cropper->add_data("source_fil_id", $this->source_file_item->id);
				$cropper->add_data("fil_id", $this->file_item->id);
				$cropper->set_width(250);
				$cropper->set_height(300);
				$cropper->set_on_success("function(response, data){
					{$this->request->get_panel()}.parent.refresh();
				}");
				$cropper->set_on_cancel("function(data){
					".\app\js::ajax("?c=dropzone/xclear_session&session_id=$session_id")."
				}");
				$this->buffer->add($cropper->build());

			$this->buffer->_div();
		$this->buffer->_div();

		$this->buffer->flush();

    }
    //--------------------------------------------------------------------------------
}

