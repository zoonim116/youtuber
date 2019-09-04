<?php

require_once( trailingslashit( dirname( __FILE__ ) ) . '/autoloader.php' );

use YoutubeR\lib\Rest;
use YoutubeR\lib\Cache;
use YoutubeR\lib\Alert;

class YoutubeR {

    private $rest;

    public function __construct() {
        $this->rest = new Rest(new WP_REST_Response(), new WP_Error());
        add_shortcode('youtuber', array($this, 'getYoutubeChannel'));
        add_action( 'rest_api_init', function() {
            $this->rest->register_routes();
        });
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_script( 'youtuber-js', plugins_url( '/assets/js/client/youtuber-frontend/dist/bundle.js', __FILE__ ));
        });
        add_action('admin_init', function () {
            //init SESSION
            if(!session_id()) {
                session_start();
            }
        });
    }

   public function addPluginMenu(){
        add_action( 'admin_menu', array( $this, 'youtuberMenu' )  );
   }
   
   public function youtuberMenu() {
       add_menu_page( 'YoutubeR', 'YoutubeR', 'administrator', 'youtuber'  , array( $this, 'youtuberDashboardPage' ) , 'dashicons-video-alt3',(string)2.05 );
       add_submenu_page( null, 'Edit Youtuber Channel', 'youtuberEditChannel', 'administrator', 'youtuberEditChannel', array( $this, 'youtuberEditChannel'));
       add_submenu_page( null, 'youtuberClearCache', 'youtuberClearCache', 'administrator', 'youtuberClearCache', array( $this, 'youtuberClearCache'));
       add_submenu_page( null, 'youtuberCreateChannel', 'youtuberCreateChannel', 'administrator', 'youtuberCreateChannel', array( $this, 'youtuberCreateChannel'));
       add_submenu_page( null, 'youtuberDeleteChannel', 'youtuberDeleteChannel', 'administrator', 'youtuberDeleteChannel', array( $this, 'youtuberDeleteChannel'));
   }

   public function youtuberDashboardPage() {
        global $wpdb;
        $youtuberChannels = $wpdb->get_results("SELECT * FROM youtuber_lists");
        $this->renderTemplate('dashboard', compact('youtuberChannels'));
   }

   public function youtuberCreateChannel() {
        if($_POST) {
            global $wpdb;
            $id = $wpdb->insert('youtuber_lists', [
                'youtube_api_key' => $_POST['youtube_api_key'],
                'youtube_chanel_id' => $_POST['youtube_chanel_id'],
                'cols_per_row' => $_POST['youtube_cols_per_row'],
                'cache_refresh' => $_POST['youtube_cache_refresh']
            ]);
            if ($id) {
                Alert::success("Channel was created");
                wp_redirect(admin_url('/admin.php?page=youtuber'), 301);
            } else {
                Alert::danger("Something went wrong. Please try again.");
            }
        }
        $this->renderTemplate('createChannel');
   }

   public function youtuberEditChannel() {
        $channelID = $_GET['id'] ? $_GET['id'] : null;
        if ($channelID) {
            global $wpdb;
            $channel = $wpdb->get_row($wpdb->prepare("SELECT * FROM youtuber_lists WHERE id = %d", $channelID));
            if ($_POST) {
                $channel->youtube_api_key = $_POST['youtube_api_key'];
                $channel->youtube_chanel_id = $_POST['youtube_chanel_id'];
                $channel->cols_per_row = $_POST['youtube_cols_per_row'];
                $channel->cache_refresh = $_POST['youtube_cache_refresh'];
                if($wpdb->replace('youtuber_lists', (array)$channel)){
                    $this->clearCache($channelID);
                    Alert::success("Channel was updated");
                } else {
                    Alert::danger("Something went wrong");
                }
                wp_redirect(admin_url('/admin.php?page=youtuber'), 301);
            }
            if ($channel) {
                $this->renderTemplate('editChannel', compact('channel'));
            }
        } else {
            Alert::danger("Pls provide Channel ID");
            wp_redirect(admin_url('/admin.php?page=youtuber'), 301);
        }
   }

   public function youtuberDeleteChannel() {
       $channelID = $_GET['id'] ? $_GET['id'] : null;
       if ($channelID) {
           global $wpdb;
           $deleted = $wpdb->delete('youtuber_lists', ['id' => $channelID], ['%d']);
           if ($deleted > 0) {
               Alert::success("Channel was removed");
           } else {
               Alert::danger("Something went wrong");
           }
           wp_redirect(admin_url('/admin.php?page=youtuber'), 301);
       } else {
           Alert::danger("Pls provide Channel ID");
           wp_redirect(admin_url('/admin.php?page=youtuber'), 301);
       }
   }

    private function renderTemplate($templateName, $data = null){
        if($data) {
            extract($data);
        }

        if ($_SESSION['youtuber-alert']) {
            $youtuberAlert = $_SESSION['youtuber-alert'];
        }
        require_once 'view/'.$templateName.'.php';
        unset($_SESSION['youtuber-alert']);
    }

    public function getYoutubeChannel($atts) {
        if($atts['id'] && is_int(intval($atts['id']))) {
            $cols = 4;
            global $wpdb;
            $youtuberRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM youtuber_lists WHERE id = %d", $atts['id']));
            if ($youtuberRow) {
                $cols = $youtuberRow->cols_per_row;
            }
            $youtuber_id = $atts['id'];
            return $this->renderTemplate('shortcode_template', compact('youtuber_id', 'cols'));
        }
        return "Error";
    }

    public function youtuberClearCache($atts) {
        $listId = $_GET['id'] ? $_GET['id'] : null;
        $this->clearCache($listId);
        wp_redirect(admin_url('/admin.php?page=youtuber'), 301);
    }

    private function clearCache($listId) {
        if ($listId) {
            global $wpdb;
            $youtubeCache = new Cache($wpdb);
            $youtubeCache->deleteFromCache($listId);
            Alert::success("Cache was cleared");
        }
    }
}