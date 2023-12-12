<?php

namespace LiquidedgeApp\Octoapp\app\app\http;

/**
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class http extends \com\http{
    //--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public static $message_arr = [
		1 => [
			"title" => "Login error",
			"message" => "Invalid username and/or password.",
		],
		2 => [
			"title" => "Invalid details",
			"message" => "Invalid username, please enter your username to continue.",
		],
		3 => [
			"title" => "Forgot password",
			"message" => "Thank you, an email has been sent to your inbox. Please check it for further instructions on resetting your password",
		],
		4 => [
			"title" => "Database error",
			"message" => "The database is unavailable at the moment, please try again later.",
		],
		5 => [
			"title" => "Not logged in",
			"message" => "You are not logged in or your session timed out, please click on the login link below.",
		],
		6 => [
			"title" => "You have an active session",
			"message" => "You are already logged in, please logout first.",
		],
		7 => [
			"title" => "Request error",
			"message" => "Your request does not exist or it has expired.",
		],
		8 => [
			"title" => "Password recovery",
			"message" => "Your new access details have been saved.",
		],
		9 => [
			"title" => "Maintenance",
			"message" => "The system is offline for planned maintenance. Thank you for your patience.",
		],
		10 => [
			"title" => "Access denied",
			"message" => "You do not have permission to access the resource you requested. If you think this is incorrect, please contact support.",
		],
		11 => [
			"title" => "Account locked",
			"message" => "You have had more than 3 failed login attempts. Your account has been locked for 15 minutes. If you continue to have problems, please contact support.",
		],
		12 => [
			"title" => "Forgot password",
			"message" => "The reCAPTCHA test failed. Please try again.",
		],
		13 => [
			"title" => "System offline",
			"message" => "The system is currently offline, we are attending to the problem.",
		],
		14 => [
			"title" => "Account inactive",
			"message" => "Your account is not active. Please contact support.",
		],
		15 => [
			"title" => "CSRF Token missing",
			"message" => "The system could not complete this action due to a missing form token. You may have cleared your browser cookies or logged in on a different tab, which could have resulted in the expiry of your current form token.",
		],
		16 => [
			"title" => "Unauthorized form submission",
			"message" => "The system could not complete this action due to an unauthorized form token. Please contact support if the problem persists.",
		],
		17 => [
			"title" => "Internal error",
			"message" => "The system encountered an error while processing your request. The administrator has been notified and will be attending to the problem. We apologize for any inconvenience. Please try again later.",
		],
		18 => [
			"title" => "Login error",
			"message" => "The system encountered an error while logging you in. For assistance, please contact support.",
		],
        19 => [
			"title" => "Content Currently Unavailable",
			"message" => "The content in this section is currently unavailable. Once the system administrator has completed setting up this section's content, you will be allowed to continue. Please try again later.",
		],
        20 => [
			"title" => "Pending Account Verification",
			"message" => "Your account is pending verification. Should we resend the verification email?",
		],
        21 => [
			"title" => "Maintenance",
			"message" => "The system is offline for planned maintenance. Thank you for your patience.",
		],
        22 => [
			"title" => "Password Expired",
			"message" => "Your password has expired. Please set your new password.",
		],
        100 => [
			"title" => "Thank you for registering",
			"message" => "A confirmation email has been sent to your email address. Please verify your email address before you continue.",
		],
        101 => [
			"title" => "Account Username / Email Changed",
			"message" => "Your account username and email address has been updated.",
		],
        102 => [
			"title" => "Account Details Updated",
			"message" => "You have successfully updated your account",
		],
        103 => [
			"title" => "Booking Request Sent",
			"message" => "Booking request successfully submitted. You will be notified by email on the next steps.",
		],
        104 => [
			"title" => "Confirmation Email Sent",
			"sub_title" => "Registration almost complete...",
			"icon" => "fa-envelope",
			"message" => [
			        "Please check your mailbox.",
			        "Each of the branches you have added need to follow the link provided in order to complete registration",
            ],
		],
        105 => [
			"title" => "Registration Request Submitted",
			"message" => "Thank you for your request, one of our representatives will contact you shortly",
		],
        106 => [
			"title" => "Thank you for verifying your email",
			"message" => "Your email has successfully been verified and your account is now active. For convenience, we have already logged you in. Please enjoy your visit with us. For any queries please dont hesitate to contact us.",
		],
        200 => [
			"title" => "Save Successful",
			"message" => "Your entry has successfully been added.",
		],
        201 => [
			"title" => "Save Pending Approval",
			"message" => "Your entry has successfully been added and is awaiting approval",
		],
        202 => [
			"title" => "Application Submitted",
			"message" => "Thank you for your application. Once we have reviewed your request, we will contact you.",
		],
        203 => [
			"title" => "Application Submitted",
			"message" => "Thank you for your application.",
		],
        404 => [
			"title" => "Error 404",
			"message" => "We're sorry, but the page you are looking for doesn't exist. You can search your topic using the box below or return to the homepage.",
		],
        500 => [
			"title" => "Internal error",
			"message" => "The system encountered an error while processing your request. The administrator has been notified and will be attending to the problem. We apologize for any inconvenience. Please try again later.",
		],
	];
	//--------------------------------------------------------------------------------
    // static
    //--------------------------------------------------------------------------------
    public static function get_stream_url($mixed, $options = []){

        $options = array_merge([
            "id" => false,
            "filename" => false,
            "absolute" => \core::$app->get_instance()->get_option("url.absolute"),
            "is_file_item" => false,
            "download" => false,
            "timestamp" => time(),
            "files_directory" => \core::$folders->get_root_files(),
        ], $options);

        $dir = $options["files_directory"];

        if(!$mixed instanceof \com\db\row && strpos($mixed, ":root_img") !== false){
            $mixed = str_replace(":root_img", \core::$folders->get_root_files()."/img", $mixed);
        }

        switch ($mixed){
            case ":root_img":
                $mixed = \core::$folders->get_root_files()."/img/{$mixed}";
                break;
            case ":logo":
                if(file_exists("{$dir}/img/logo.png")) $mixed = "{$dir}/img/logo.png";
                else $mixed = "{$dir}/standard/placeholder-maxarea_300x200.jpg";
                break;
        }

		if (!$options["id"]) {
			if ($mixed instanceof \com\db\row) {
				$options["id"] = base64_encode($mixed->id);
				$options["timestamp"] = strtotime($mixed->fil_date_updated);
				$options["is_file_item"] = true;
				if(!$options["filename"]) $options["filename"] = $mixed->fil_filename;
			} else if (is_string($mixed)) {
				$options["id"] = base64_encode($mixed);
				$options["timestamp"] = filemtime($mixed);
				$options["is_file_item"] = false;
				if(!$options["filename"]) $options["filename"] = basename($mixed);
			}
		}

        $url_parts = [];
        $url_parts["id"] = urlencode($options["id"]);
        $url_parts["is_file_item"] = $options["is_file_item"];
        $url_parts["filename"] = $options["filename"];
        $url_parts["cache"] = $options["timestamp"];
        if($options["download"]) $url_parts["download"] = 1;

        $url_str = "";
        foreach ($url_parts as $id => $value){
            $url_str .= "&{$id}={$value}";
        }

        $url = "/index.php?c=index/xstream_file".$url_str;

        if($options["absolute"]){
            $url = \core::$app->get_instance()->get_url().$url;
        }

        return $url;
    }
    //--------------------------------------------------------------------------------
	/**
	 * Encodes and prints the given variable as JSON. Also sets the header mimetype to
	 * json.
	 *
	 * @param [any] $var - the variable to encode and print
     * @return string
	 */
    public static function json($var) {
		// set correct mime type
		header("Content-Type: application/json");

		// encode and print variable
    	echo json_encode($var);

    	return "stream";
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $context
	 * @param false $db_obj
	 * @param array $options
	 * @return string
	 */
    public static function get_seo_url($context, $db_obj = false, $options = []): string {

	    return \LiquidedgeApp\Octoapp\app\app\seo\seo::make()->get_url($context, $db_obj, $options);

    }
    //--------------------------------------------------------------------------------
    public static function get_seo_control($context, $options = []): string {
        $seo = \LiquidedgeApp\Octoapp\app\app\seo\seo::make();
        $item = $seo->get_context($context);

        return $item ? $item->control : false;
    }
    //--------------------------------------------------------------------------------
    public static function get_current_url() {
        return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    //--------------------------------------------------------------------------------
    public static function get_slug($options = []) {
	    return \core::$app->get_request()->get("slug", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, ["get" => true]);
    }
	//--------------------------------------------------------------------------------
    public static function add_stream_headers($filename, $options = []) {

        // options
		$options = array_merge([
			"download" => true,
			"noindex" => true,
		], $options);


    	// clear output buffer
		if (ob_get_level()) ob_end_clean();

		// add stream headers
		header("Pragma: public");
		header('Cache-Control: public');
        header("Cache-Control: max-age=".((60*60*24*365)));
        if($options["noindex"]) header("X-Robots-Tag: nofollow");

        $timestamp = strtotime("now + 1 week");
        $gmt_mtime = gmdate('r', $timestamp);

        header('ETag: "'.md5($filename).'"');
        header('Expires: '.  $gmt_mtime);

		header("Content-Description: File Transfer");
		header("Content-Transfer-Encoding: binary");
		header("HTTP/1.1 200 OK");

		 // content-type
		$content_type = self::get_mime_type(pathinfo($filename, PATHINFO_EXTENSION));
		header("Content-Type: {$content_type}");

		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime) {
                header('HTTP/1.1 304 Not Modified');
                exit();
            }
            if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp.$filename)) {
                header('HTTP/1.1 304 Not Modified');
                exit();
            }
        }

        if ($options["download"]) {
			header('Content-Disposition: attachment; filename="'.$filename.'"');
		}else{
            header('Content-Disposition:filename="'.$filename.'"');
        }

	}
	//--------------------------------------------------------------------------------
	public static function stream($data, $filename, $options = []) {
		// options
		$options = array_merge([
			"download" => true,
		], $options);

		self::add_stream_headers($filename, $options);

		if (is_resource($data)) {
			while (!feof($data)) {
				echo fread($data, 8192);
				if(ob_get_level()) ob_flush();
			}
		}
		else {
			echo $data;
		}

		return "stream";
	}
	//--------------------------------------------------------------------------------
	public static function stream_file($filepath, $options = []) {
		// options
		$options = array_merge([
			"filename" => false,
			"download" => true,
		], $options);

		// check if our file exists
		if (!file_exists($filepath)) return;

		// stream
		$file = fopen($filepath, "r");
		self::stream($file, ($options["filename"] ? $options["filename"] : basename($filepath)), ["download" => $options["download"]]);
		fclose($file);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param array $options = [
     *      'redirect' => 'an url to redirect to'
     *      'message' => 'a message to show in an alert popup'
     *      'refresh' => 'boolean - refresh the current page'
     *      'popup' => 'an url to create a popup with'
     *      'js' => 'custom js to trigger'
     * ]
     * @return string
     */
    public static function ajax_response($options = []) {

        $options = array_merge([
        	"code" => 0,
            "redirect" => false,
            "alert" => false,
            "message" => false,
            "title" => false,
            "ok_callback" => false,
            "refresh" => false,
            "popup" => false,
            "notice" => false,
            "notice_color" => "white",
            "notice_background" => "info",
            "js" => false,
        ], $options);

        message(false);

		return \LiquidedgeApp\Octoapp\app\app\http\http::json($options);
    }
    //--------------------------------------------------------------------------------
	public static function get_error($nr) {

		if(!$nr || !isset(\LiquidedgeApp\Octoapp\app\app\http\http::$message_arr[$nr])) $nr = 17;

		$return = \LiquidedgeApp\Octoapp\app\app\http\http::$message_arr[$nr];

		$return = array_merge([
            "title" => false,
            "sub_title" => false,
            "message" => false,
            "icon" => "fa-exclamation-circle",
        ], $return);

		return $return;
	}
	//--------------------------------------------------------------------------------
    public static function is_mobile() {
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)));
    }
    //--------------------------------------------------------------------------------
    public static function is_control($control_arr, $options = []) {

        $control = \core::$app->get_request()->get_resource()."/".\core::$app->get_request()->get_action();
        $control_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($control_arr);

        return in_array($control, $control_arr);
    }
    //--------------------------------------------------------------------------------
    public static function clear_session_redirect($options = []) {

        //get session redirect
        $session = \core::$app->get_session();

        if($session){
        	$session->redirect_url = $session->redirect_control = http::get_seo_url("ui_home");
		}
    }
    //--------------------------------------------------------------------------
    public static function strip_tags($string, $options = []) {
	    $options_arr = array_merge([
	        "utf_enabled" => true,
	        "separator" => '-',
	    ], $options);

	    $separator = $options_arr["separator"];

	    $quoteSeparator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;' => '',
            '[^\w\d _-]' => '',
            '\s+' => $separator,
            '(' . $quoteSeparator . ')+' => $separator
        );

        $string = strip_tags($string);
        foreach ($trans as $key => $val) {
            $string = preg_replace('#' . $key . '#i' . ($options_arr["utf_enabled"] ? 'u' : ''), $val, $string);
        }

        return $string;
    }
    //--------------------------------------------------------------------------------
	public static function go_error_frontend($nr) {
		self::redirect(http::get_seo_url("ui_error", $nr));
	}
    //--------------------------------------------------------------------------------
    public static function redirect($url, $options = []) {
		// options
		$options = array_merge([
			"delay" => false,
		], $options);

		 // force javascript redirect for ajax view
    	if (!$options["delay"] && self::is_ajax_request()) $options["delay"] = 1;

    	if ($options["delay"]) {
	        ?>
			<script type="text/javascript" language="javascript">
				setTimeout(function() { document.location = '<?= $url; ?>'; }, <?= $options["delay"]; ?>);
			</script>
			<?php
	        if (ob_get_level()) ob_end_flush();
    	}
    	else {
	        if (ob_get_level()) ob_end_clean();
        	header("Location: {$url}");
    	}
        exit();

    	return null;
    }
    //--------------------------------------------------------------------------------
	public static function set_session_redirect($url, $options = []) {

	    $options = array_merge([
	        "type" => "redirect_url"
	    ], $options);

	    //get session redirect
        $session = \core::$app->get_session();
        if($session) $session->{$options["type"]} = $url;
    }
	//--------------------------------------------------------------------------------
	public static function go_home_frontend($options = []) {
        self::redirect(http::get_seo_url("ui_home"), $options);
    }
	//--------------------------------------------------------------------------------
	public static function get_session_redirect($options = []) {

	    $options = array_merge([
	        "default" => http::get_seo_url("ui_home")
	    ], $options);

	    $url = $options["default"];

	    //get session redirect
        $session = \core::$app->get_session();
        if($session) {
            $url = $session->get("redirect_url", $options["default"]);
        }

        return $url;
    }
	//--------------------------------------------------------------------------------
}