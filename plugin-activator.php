<?php

/*
	# Plugin Name: YoutubeR
	# Version: 1.0
	# Description: Plugin to display Youtube playlists 
	# Author: Maxim
*/

require_once __DIR__.'/YoutubeR.php';

$youtuber = new YoutubeR();
$youtuber->addPluginMenu();