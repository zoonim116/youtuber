<?php


namespace YoutubeR\lib;


class Alert
{
    public static function success($text) {
        $_SESSION['youtuber-alert']['type'] = "success";
        $_SESSION['youtuber-alert']['text'] = $text;
    }

    public static function danger($text) {
        $_SESSION['youtuber-alert']['type'] = "danger";
        $_SESSION['youtuber-alert']['text'] = $text;
    }

    public static function warning($text) {
        $_SESSION['youtuber-alert']['type'] = "warning";
        $_SESSION['youtuber-alert']['text'] = $text;
    }

}