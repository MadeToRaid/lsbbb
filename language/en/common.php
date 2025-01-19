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

	'LSBBB_HELLO'			=> 'Hello %s!',
	'LSBBB_GOODBYE'			=> 'Goodbye %s!',

	'LSBBB_EVENT'			=> ' :: lsbBB Event :: ',

	'ACP_LSBBB_GOODBYE'			=> 'Should say goodbye?',
	'ACP_LSBBB_SETTING_SAVED'		=> 'Settings have been saved successfully!',

	'LSBBB_NOTIFICATION'			=> 'lsbBB notification',

	'LSBBB_PAGE'				=> 'FFXI',
	'LSBBB_AH'					=> 'Auction House',
	'LSBBB_MY_LISTINGS'			=> 'My Listings',
	'LSBBB_CHARACTER'			=> 'Character',
	'LSBBB_CHARACTERS'			=> 'Characters',
	'LSBBB_WORLD_MAP'			=> 'World Map',
	'LSBBB_ZONE'				=> 'Zone',
	'LSBBB_CONNECTIONS'			=> 'Connections',
	'LSBBB_ITEM'				=> 'Item',
	'LSBBB_PARTY'				=> 'Party',
	'LSBBB_RECORD'				=> 'Record',
	'LSBBB_NPC'					=> 'NPC',
	'LSBBB_MOB'					=> 'Mob',
	'LSBBB_FAMILY'				=> 'Family',
	'LSBBB_LEVEL'				=> 'Level',
	'LSBBB_FOR_SALE'			=> 'For Sale',
	'LSBBB_LIST_PRICE'			=> 'Price',
	'LSBBB_BASE_SELL'			=> 'Base Sell',
	'LSBBB_AVERAGE'				=> 'Average',
	'LSBBB_RATE'				=> 'Rate',
	'LSBBB_SALE'				=> 'Sale',
	'LSBBB_SELLER'				=> 'Seller',
	'LSBBB_BUYER'				=> 'Buyer',
	'LSBBB_SALE_HISTORY'			=> 'Sale History',
	'LSBBB_RETURN_ITEM'			=> 'Return Item',
	'LSBBB_BATTLEFIELD'			=> 'Battlefield',

	'LSBBB_WINDOWS'				=> "Windows",
	'LSBBB_LINUX'				=> "Linux",
	'LSBBB_STEAMDECK'			=> "Steam Deck",

	'LSBBB_VANADIEL'			=> "Vana'diel",

	'LSBBB_NAV_INDEX'			=> 'FFXI',
	'LSBBB_NAV_INSTALL'			=> 'Install',
	'LSBBB_NAV_WORLDMAP'		=> 'World Map',
	'LSBBB_NAV_AUCTIONHOUSE'	=> 'Auction House',
	'LSBBB_NAV_CHARACTERS'		=> 'Characters',
	'LSBBB_NAV_MYLISTINGS'		=> 'My Listings',

	'LSBBB_VIEWING_INDEX'			=> '[lsbBB] Index',
	'LSBBB_VIEWING_INSTALL'			=> '[lsbBB] Install',
	'LSBBB_VIEWING_ITEM'			=> '[lsbBB] Item',
	'LSBBB_VIEWING_AUCTIONHOUSE'	=> '[lsbBB] Auction House',
	'LSBBB_VIEWING_ZONE'			=> '[lsbBB] Zone',
	'LSBBB_VIEWING_WORLDMAP'		=> '[lsbBB] World Map',
	'LSBBB_VIEWING_CHARACTER'		=> '[lsbBB] Character',
	'LSBBB_VIEWING_MOB'				=> '[lsbBB] Mob',
	'LSBBB_VIEWING_NPC'				=> '[lsbBB] NPC',
]);
