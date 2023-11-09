<?php
namespace LiquidedgeApp\Octoapp\app\app\inc\google;

/**
 * @package app\inc\google
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class helper extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
    //--------------------------------------------------------------------------------
    public static function get_map_directions_link($address) {

        $address_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($address);

        return "https://www.google.com/maps/dir/?api=1&destination=".urlencode(implode("+", $address_arr));
    }
    //--------------------------------------------------------------------------------
}