<?php


namespace YoutubeR\lib;


/**
 * Class Cache
 * @package YoutubeR\lib
 */
class Cache
{
    private $_wpdb;
    private $_table = "youtuber_cache_data";

    public function __construct($wpdb)
    {
        $this->_wpdb = $wpdb;
    }

    /**
     * @param $list_id
     * @param string $nextPageToken
     * @return mixed
     */
    public function getFromCache($list_id, $nextPageToken='') {
        if (!empty($nextPageToken)) {
            return $this->_wpdb->get_row($this->_wpdb->prepare("SELECT * FROM ".$this->_table." WHERE list_id = %d AND page_token LIKE %s", $list_id, $this->_wpdb->esc_like($nextPageToken)));
        } else {
            return $this->_wpdb->get_row($this->_wpdb->prepare("SELECT * FROM ".$this->_table." WHERE list_id = %d", $list_id));
        }
    }

    public function addToCache($list_id, $pageToken, $data, $expiration) {
        return $this->_wpdb->insert($this->_table, [
            'list_id' => $list_id,
            'data' => json_encode($data),
            'page_token' => $pageToken,
            'expires' => time() + ($expiration * 60)
        ], ['%d', '%s', '%s', '%d']);
    }

    public function updateCache($row) {
        return $this->_wpdb->replace($this->_table, (array)$row);
    }

    public function deleteFromCache($list_id) {
        return $this->_wpdb->delete($this->_table, [
            'list_id' => $list_id
        ]);
    }
}