<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

if (!defined('IN_PHPBB')) {
	exit;
}

if (empty($lang) || !is_array($lang)) {
	$lang = [];
}

$lang = array_merge($lang, [
	'ACP_LSBBB'					=> 'lsbBB Settings',
	'ACP_LSBBB_TITLE'			=> 'lsbBB',
	'ACP_LSBBB_YT_SETTINGS'		=> 'YouTube Settings',
	'ACP_LSBBB_YOUTUBE_KEY'		=> 'Data API Key',
	'ACP_LSBBB_YOUTUBE_NM'		=> 'Enable NM Videos',
	'ACP_LSBBB_YOUTUBE_BF'		=> 'Enable Battlefield Videos',
	'ACP_LSBBB_SETTING_SAVED'	=> 'lsbBB Settings Saved',


	'LOG_ACP_LSBBB_SETTINGS'	=> '<strong>lsbBB settings updated</strong>',
]);
