<?php

namespace action\index;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xset_session_redirect implements \com\router\int\action {

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {

        $url = $this->request->get('url', \com\data::TYPE_STRING);

        //login redirect
		$this->session->redirect_url = $url;
        return "stream";

	}
	//--------------------------------------------------------------------------------
}