<?php


namespace YoutubeR\lib;

use DateTime;
use DateInterval;

class Helper
{
    public static function covtime($youtube_time) {
        if ($youtube_time) {
            $start = new DateTime('@0');
            $start->add(new DateInterval($youtube_time));
            return $start->format('i:s');
        }
        return "";
    }
}