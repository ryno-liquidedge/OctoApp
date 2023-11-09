<?php
namespace LiquidedgeApp\Octoapp\app\app\data\inc\youtube;

/**
 * @package app\inc\youtube
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class youtube extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
    //--------------------------------------------------------------------------------
    public static function is_youtube_link($link) {

        if(!$link) return false;

        if(strpos($link, "youtu.be") !== false){
            return true;
        }else if(strpos($link, "youtube.com") !== false){
            return true;
        }
        return false;
    }
    //--------------------------------------------------------------------------------
    public static function get_youtube_id_from_link($link) {

        if(!$link) return false;

        if(strpos($link, "youtu.be") !== false){
            $parts = explode("/", $link);
            return end($parts);
        }else if(strpos($link, "youtube.com") !== false){
            $parts = parse_url($link);
            $result_arr = [];
            parse_str($parts["query"], $result_arr);

            return isset($result_arr["v"]) ? $result_arr["v"] : false;
        }
    }
    //--------------------------------------------------------------------------------
    public static function get_youtube_link($id) {
        return "https://www.youtube.com/watch?v={$id}";
    }
    //--------------------------------------------------------------------------------
    public static function get_youtube_data_from_link($link) {

        $id = self::get_youtube_id_from_link($link);

        $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id);
        parse_str($content, $ytarr);
        $data = json_decode($ytarr["player_response"]);

        $return = [];
        if(is_object($data) && property_exists($data, "videoDetails")){
            $return["id"] = $data->videoDetails->videoId;
            $return["title"] = $data->videoDetails->title;
            $return["short_description"] = $data->videoDetails->shortDescription;
            $return["length"] = $data->videoDetails->lengthSeconds;
        }

        return $return;
    }
    //--------------------------------------------------------------------------------
}