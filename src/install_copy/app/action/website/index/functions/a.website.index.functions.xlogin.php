<?php

namespace action\website\index\functions;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class xlogin implements \com\router\int\action {

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

        // params
		$panel = $this->request->get("panel", \com\data::TYPE_STRING, ["default" => "mod"]);
		$per_username = $this->request->get("per_username", \com\data::TYPE_STRING);
		$per_password = $this->request->get("per_password", \com\data::TYPE_STRING);

		// login
		$code = \app\user::login_frontend($per_username, $per_password, true);
		switch ($code){
            case 0: return \app\http::ajax_response(["redirect" => "?c=index/xhome", "no_overlay" => true]);
            case 6: return \app\http::ajax_response(["redirect" => \app\http::get_seo_url("ui_error", 6)]);
            case 22:
                $message_arr = \app\http::$message_arr;
                $message_data = isset($message_arr[$code]) ? $message_arr[$code] : $message_arr[500];
                return \app\http::ajax_response([
                    "title" => $message_data["title"],
                    "alert" => $message_data["message"],
                    "ok_callback" => "{$panel}.popup('?c=website.index.modal/forgot_password', {data:{per_username:'{$per_username}'}, width:'modal-md'})",
                ]);

            default:
                $message_arr = \app\http::$message_arr;
                $message_data = isset($message_arr[$code]) ? $message_arr[$code] : $message_arr[500];

                return \app\http::ajax_response([
                    "title" => $message_data["title"],
                    "alert" => $message_data["message"],
                ]);
        }
    }
    //--------------------------------------------------------------------------------
}

