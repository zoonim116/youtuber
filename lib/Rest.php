<?php

namespace YoutubeR\lib;

use YoutubeR\lib\Helper;
use YoutubeR\lib\Cache;

/**
 * Class Rest
 * @package YoutubeR\lib
 */
class Rest
{

    private $wp_response;
    private $wp_error;

    public function __construct($wp_response, $wp_error)
    {
        $this->wp_response = $wp_response;
        $this->wp_error = $wp_error;
    }

    public function register_routes()
    {
        $namespace = 'youtuber/v1';
        $path = 'videos/(?P<youtuber_id>\d+)/';
        register_rest_route($namespace, '/' . $path, [
            array(
                'methods' => 'GET',
                'callback' => array($this, 'getVideos'),
                'args' => array(
                    'youtuber_id' => array(
                        'required' => true,
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    )
                ),
            ),
        ]);

        $path = 'videos/(?P<youtuber_id>\d+)/(?P<page_token>.+)';
        register_rest_route($namespace, '/' . $path, [
            array(
                'methods' => 'GET',
                'callback' => array($this, 'getVideos'),
                'args' => array(
                    'youtuber_id' => array(
                        'required' => true,
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                    'page_token' => array(
                        'required' => true,
                        'default' => '',
                    ),
                ),
            ),
        ]);
    }

    /**
     * @param $request
     * @return mixed
     */

    public function getVideos($request)
    {
        global $wpdb;
        $youtuberCache = new Cache($wpdb);
        $youtuberRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM youtuber_lists WHERE id = %d", $request['youtuber_id']));
        if (!$youtuberRow) {
            return $this->wp_error->add(404, 'no_videos', 'there is no videos');
        }

        $pageToken = "";
        if ($request['page_token']) {
            $pageToken = $request['page_token'];
        }
        $cachedData = $youtuberCache->getFromCache($request['youtuber_id'], $pageToken);

        if ($cachedData && $cachedData->expires >= time()) {
            $videoList = json_decode($cachedData->data);
        } else {
            $videoList = $this->loadVideosFromYoutube($youtuberRow->youtube_chanel_id, $youtuberRow->youtube_api_key, $youtuberRow->cols_per_row, $pageToken);
            foreach ($videoList->items as $key => $item) {
                $videoInfo = $this->loadAdditionalVideoInfo($youtuberRow->youtube_api_key, $item->id->videoId);
                $videoList->items[$key]->statistics = $videoInfo->items[0]->statistics;
                $videoList->items[$key]->statistics->duration = Helper::covtime($videoInfo->items[0]->contentDetails->duration);
            }
            if ($videoList) {
                $videoList->channelID = $youtuberRow->youtube_chanel_id;
                if ($cachedData) {
                    $cachedData->data = json_encode($videoList);
                    $cachedData->expires = time() + ($youtuberRow->cache_refresh * 60);
                    if(!$youtuberCache->updateCache($cachedData)) {
                        return $this->wp_error('update_cache_error', 'error while updating cache', array('status' => 404));
                    }
                } else {
                    $youtuberCache->addToCache($request['youtuber_id'], $pageToken, $videoList, $youtuberRow->cache_refresh);
                }
            } else {
                $videoList = json_decode($cachedData->data);
            }

        }
        $videoList->lazyload = get_option('lazyload_optimization') ? (int)get_option('lazyload_optimization') : 0;
        $this->wp_response->set_data($videoList);
        $this->wp_response->set_status(200);

        return $this->wp_response;
    }

    /**
     * @param $youtubeChannelId
     * @param $youtubeApiKey
     * @param $maxResults
     * @param string $pageToken
     * @return array|mixed|object
     */
    private function loadVideosFromYoutube($youtubeChannelId, $youtubeApiKey, $maxResults, $pageToken = '')
    {
        $query = 'https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=' . trim($youtubeChannelId) . '&maxResults=' . trim($maxResults) . '&key=' . trim($youtubeApiKey) . '';
        if (!empty($pageToken)) {
            $query .= "&pageToken=" . trim($pageToken);
        }
        return json_decode(file_get_contents($query));
    }

    /**
     * @param $youtubeApiKey
     * @param $videoID
     * @return array|mixed|object
     */
    private function loadAdditionalVideoInfo($youtubeApiKey, $videoID)
    {
        return json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics,contentDetails&id=$videoID&key=$youtubeApiKey"));
    }
}