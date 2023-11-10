<?php

namespace action\dropzone;

/**
 * @package action\dropzone
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class xclear_session implements \com\router\int\action {

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

		//init session
		$this->dropzone_session =  \app\inc\dropzone\session::make(["id" => $session_id]);
		$this->dropzone_session->clear();

    }
    //--------------------------------------------------------------------------------
}

