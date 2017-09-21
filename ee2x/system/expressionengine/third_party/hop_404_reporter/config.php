<?php if ( ! defined('EXT')) exit('No direct script access allowed');

/**
 * Hop 404 Reporter - Config
 *
 * NSM Addon Updater config file.
 *
 * @package		Hop Studios:Hop 404 Reporter
 * @author		Hop Studios, Inc.
 * @copyright	Copyright (c) 2014, Hop Studios, Inc.
 * @link		http://www.hopstudios.com/software/versions/hop_404_reporter
 * @version		1.0.3
 * @filesource	hop_404_reporter/config.php
 */

$config['name']='Hop 404 Reporter';
$config['version']='1.0.3';
$config['nsm_addon_updater']['versions_xml']='http://www.hopstudios.com/software/versions/hop_404_reporter';

// Version constant
if (!defined("HOP_404_REPORTER_VERSION")) {
	define('HOP_404_REPORTER_VERSION', $config['version']);
}
