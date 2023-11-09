<?php

namespace LiquidedgeApp\Octoapp\app\app\date;

/**
 * Class data
 * @package app
 * @author Ryno Van Zyl
 */

class date extends \com\date{

    /**
        d - The day of the month (from 01 to 31)
        D - A textual representation of a day (three letters)
        j - The day of the month without leading zeros (1 to 31)
        l (lowercase 'L') - A full textual representation of a day
        N - The ISO-8601 numeric representation of a day (1 for Monday, 7 for Sunday)
        S - The English ordinal suffix for the day of the month (2 characters st, nd, rd or th. Works well with j)
        w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
        z - The day of the year (from 0 through 365)
        W - The ISO-8601 week number of year (weeks starting on Monday)
        F - A full textual representation of a month (January through December)
        m - A numeric representation of a month (from 01 to 12)
        M - A short textual representation of a month (three letters)
        n - A numeric representation of a month, without leading zeros (1 to 12)
        t - The number of days in the given month
        L - Whether it's a leap year (1 if it is a leap year, 0 otherwise)
        o - The ISO-8601 year number
        Y - A four digit representation of a year
        y - A two digit representation of a year
        a - Lowercase am or pm
        A - Uppercase AM or PM
        B - Swatch Internet time (000 to 999)
        g - 12-hour format of an hour (1 to 12)
        G - 24-hour format of an hour (0 to 23)
        h - 12-hour format of an hour (01 to 12)
        H - 24-hour format of an hour (00 to 23)
        i - Minutes with leading zeros (00 to 59)
        s - Seconds, with leading zeros (00 to 59)
        u - Microseconds (added in PHP 5.2.2)
        e - The timezone identifier (Examples: UTC, GMT, Atlantic/Azores)
        I (capital i) - Whether the date is in daylights savings time (1 if Daylight Savings Time, 0 otherwise)
        O - Difference to Greenwich time (GMT) in hours (Example: +0100)
        P - Difference to Greenwich time (GMT) in hours:minutes (added in PHP 5.1.3)
        T - Timezone abbreviations (Examples: EST, MDT)
        Z - Timezone offset in seconds. The offset for timezones west of UTC is negative (-43200 to 50400)
        c - The ISO-8601 date (e.g. 2013-05-05T16:34:42+00:00)
        r - The RFC 2822 formatted date (e.g. Fri, 12 Apr 2013 12:01:05 +0200)
        U - The seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
     */

    /**
     * output date format: 24-03-12
     */
    public static $DATE_FORMAT_01 = "d-m-y";
    /**
     * output date format: Saturday 24th March 2012
     */
    public static $DATE_FORMAT_02 = "l\, jS F Y";
    /**
     * output date format: 5:45pm on Saturday 24th March 2012
     */
    public static $DATE_FORMAT_03 = "g:ia \o\\n l jS F Y";
    /**
     * output date format: 24th March 2012
     */
    public static $DATE_FORMAT_04 = "jS F Y";
    /**
     * output date format: 15:12:15
     */
    public static $DATE_FORMAT_05 = "H:i:s";
    /**
     * output date format: 15:12
     */
    public static $DATE_FORMAT_06 = "H:i";
    /**
     * output date format: 24/03/12
     */
    public static $DATE_FORMAT_07 = "m/d/Y";
    /**
     * output date format: 24th March 2012, 5:45pm
     */
    public static $DATE_FORMAT_08 = "jS F Y\, g:ia";
    /**
     * output date format: 5:45pm - 24th March 2012
     */
    public static $DATE_FORMAT_09 = "g:ia \- jS F Y";
    /**
     * output date format: 24th Mar, 5:45pm
     */
    public static $DATE_FORMAT_10 = "jS M Y\, g:ia";
    /**
     * output date format: 24th Mar
     */
    public static $DATE_FORMAT_11 = "jS M";
    /**
     * output date format: Mar 24th
     */
    public static $DATE_FORMAT_12 = "M jS \- g:ia";
    /**
     * output date format: 5:45pm
     */
    public static $DATE_FORMAT_13 = "g:ia";
    /**
     * output date format: 5:45pm 24/03/12
     */
    public static $DATE_FORMAT_14 = "g:ia m/d/Y";

 	//--------------------------------------------------------------------------------
 	// functions
	//--------------------------------------------------------------------------------
    public static function is_date($date, $format = "Y-m-d G:i:s"){
        if (\DateTime::createFromFormat($format, $date) !== FALSE) {
            return $date;
        }
        return false;
    }
	//--------------------------------------------------------------------------------
    public static function get_first_date_of_month($date, $format = "Y-m-d"): string {
        $d = new \DateTime($date);
		$d->modify('first day of this month');
		return $d->format($format);
    }
	//--------------------------------------------------------------------------------
    public static function get_last_date_of_month($date, $format = "Y-m-d"): string {
        $d = new \DateTime($date);
		$d->modify('last day of this month');
		return $d->format($format);
    }
	//--------------------------------------------------------------------------------
    public static function is_weekend_day_today(): bool {
        $day = strtolower(self::strtodate("today", "l"));
        if(($day == "saturday" )|| ($day == "sunday")){
            return true;
        }
        return false;
    }
	//--------------------------------------------------------------------------------
    public static function is_weekend($date): bool {
        return (date('N', strtotime($date)) >= 6);
    }
    //--------------------------------------------------------------------------------
    public static function is_weekday($date): bool {
        return (date('N', strtotime($date)) < 6);
    }
	//--------------------------------------------------------------------------------

    /**
     * @param string $format - M | m | F | n
     * @return array
     */
    public static function get_calendar_months_arr($format = "F"): array {

        $current_year = self::strtodate("today", "Y");

        $return_arr = [];
        $date_arr = self::get_datetime_arr("$current_year-01-01", "$current_year-12-31", "+ 1 month", "Y-m-d");

        array_walk($date_arr, function(&$item) use($format, &$return_arr){
            $return_arr[self::strtodate($item, "n")] = self::strtodate($item, $format);
        });

        return $return_arr;
    }
	//--------------------------------------------------------------------------------
    public static function get_week_from_date($date = "now", $step = '+ 1 hour'): array {
        $ts = strtotime($date);
        $start = (date('N', $ts) == 1) ? $ts : strtotime('last monday', $ts);
        $end = date('Y-m-d', strtotime('next sunday', $start));

        $return = [];

        $result_arr = self::get_datetime_arr(date('Y-m-d', $start), $end, $step);
        if(count($result_arr) == 7){
            foreach ($result_arr as $date) {
                $day_of_week = self::strtodatetime($date, "l");
                $return[strtoupper($day_of_week)] = $date;
            }
        }

        return $return;
    }
    //--------------------------------------------------------------------------------

	/**
	 * Takes a nova datetime stamp, and substitutes the year in the timestamp with the new year
	 * @param $start_date
	 * @param $end_date
	 * @param string $step
	 * @param string $format
	 * @return array
	 */
    public static function get_datetime_arr( $start_date, $end_date, $step = '+ 1 hour', $format = "Y-m-d G:i:s" ): array {
        $dates = array();
        $current = self::strtodatetime($start_date);
        $last = self::strtodatetime($end_date);

        while( $current <= $last ) {
        	$_date = self::strtodatetime($current, $format);
            $dates[$_date] = $_date;
            $current = self::strtodatetime("$current $step");
        }

        return $dates;
    }
    //--------------------------------------------------------------------------------
	public static function get_timer_arr(): array {
		return self::$timer_arr;
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param false $start_date
	 * @param false $end_date
	 * @param string $format
	 * @param array $options
	 * @return array
	 */
    public static function get_month_arr( $start_date = false, $end_date = false, $format = "Y-m-01", $options = []): array {

    	$options = array_merge([
    	    "value_format" => $format
    	], $options);

    	if(!$start_date) $start_date = self::strtodate("today", "Y-01-01");
    	if(!$end_date) $end_date = self::strtodate("today", "Y-12-31");

        $dates = array();
        $current = self::strtodate($start_date, $format);
        $last = self::strtodate($end_date, $format);

        while( $current <= $last ) {
        	$index = self::strtodate($current, $format);
        	$value = self::strtodate($current, $options["value_format"]);
            $dates[$index] = $value;
            $current = self::strtodate("$index + 1 month");
        }

        return $dates;
    }
    //--------------------------------------------------------------------------------
	/**
	 * gets an array of 24h
	 * @return array
	 */
	public static function get_time_arr($options = []): array {

		$options = array_merge([
		    "start_time" => "00:00",
		    "end_time" => "23:59",
		    "format" => "G:i",
		    "increment" => "+ 1 hour",
		], $options);

		$start_time = self::strtodatetime("now", "Y-m-d {$options["start_time"]}:00");
		$end_time = self::strtodatetime("now", "Y-m-d {$options["end_time"]}:00");

		return self::get_datetime_arr($start_time, $end_time, $options["increment"] , $options["format"]);
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $start_time
	 * @param $end_time
	 * @return int
	 */
	public static function count_hours($start_time, $end_time): int {

		$time_arr = self::get_time_arr([
			"start_time" => $start_time,
			"end_time" => $end_time,
		]);

		return sizeof($time_arr);

	}
    //--------------------------------------------------------------------------------
    public static function get_year_list($start_year = 1970, $end_year = false): array {

        if(!$end_year) $end_year = self::strtodate("today", "Y");

        $return = [];

        foreach(range($start_year, (int)date("Y")) as $year) {
            $return[$year] = $year;
            if($end_year == $year) { break; }
        }
        asort($return);

        return $return;
    }
    //--------------------------------------------------------------------------------
    public static function get_weeks_arr(): array {
        $week_arr = [];
        for ($index = 1; $index <= 52; $index++) {
            $week_arr[$index] = $index > 1 ? "$index Weeks" : "$index Week";
        }
        return $week_arr;
    }
    //--------------------------------------------------------------------------------
	public static function hour_diff($date1, $date2) {
		return floor((strtotime($date2) - strtotime($date1)) / 60) / 60;
	}
    //--------------------------------------------------------------------------------
    public static function is_between($date, $date_start, $date_end): bool {

		$date = self::strtodate($date);
		$date_start = self::strtodate($date_start);
		$date_end = self::strtodate($date_end);

		if(isnull($date)) return false;
		if(isnull($date_start)) return false;
		if(isnull($date_end)) return false;

		if (($date >= $date_start) && ($date <= $date_end)) return true;
		return false;
    }
    //--------------------------------------------------------------------------------
	public static function parse_microtime($str): string {
		list($usec, $sec) = explode(' ', $str); //split the microtime on space
		$usec = str_replace("0.", ".", $usec);     //remove the leading '0.' from usec
		return date('H:i:s', $sec) . $usec;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $start_time
	 * @param $end_time
	 * @return int
	 */
	public static function get_count_hours($start_time, $end_time) {

		$time_arr = self::get_time_arr([
			"start_time" => $start_time,
			"end_time" => $end_time,
		]);

		return sizeof($time_arr)-1;

	}
    //--------------------------------------------------------------------------------

}
