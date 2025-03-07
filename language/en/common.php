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
	'LSBBB_EVENT'			=> ' :: lsbBB Event :: ',

	'LSBBB_PAGE'				=> 'FFXI',
	'LSBBB_AH'					=> 'Auction House',
	'LSBBB_MY_LISTINGS'			=> 'My Listings',
	'LSBBB_CHARACTER'			=> 'Character',
	'LSBBB_CHARACTERS'			=> 'Characters',
	'LSBBB_WORLD_MAP'			=> 'World Map',
	'LSBBB_ZONE'				=> 'Zone',
	'LSBBB_ZONENOTFOUND'		=> 'Zone Not Found',
	'LSBBB_ZONE_AREA'			=> 'Area',
	'LSBBB_POSITION'			=> 'Position',
	'LSBBB_CONNECTIONS'			=> 'Connections',
	'LSBBB_CONTENT_TAG'			=> 'Content Tag',
	'LSBBB_VIDEOS'				=> 'Videos',
	'LSBBB_SPAWN'				=> 'Spawn',
	'LSBBB_MOBID'				=> 'Mob ID',
	'LSBBB_MOBHEX'				=> 'Mob HEX',

	'LSBBB_SHOWUNLISTED'		=> 'Show Unlisted Items',
	'LSBBB_HIDEUNLISTED'		=> 'Hide Unlisted Items',
	'LSBBB_AUCTIONCATEGORY'		=> 'Auction Category',
	'LSBBB_CATEGORYNOTFOUND'	=> 'Category Not Found',
	'LSBBB_ITEM'				=> 'Item',
	'LSBBB_ITEMNOTFOUND'		=> 'Item Not Found',
	'LSBBB_DEFAULT_SEARCH'		=> 'Chocobo',
	'LSBBB_PARTY'				=> 'Party',
	'LSBBB_RECORD'				=> 'Record',
	'LSBBB_NPC'					=> 'NPC',
	'LSBBB_MOB'					=> 'Mob',
	'LSBBB_MOB_GROUP'			=> 'Mob Group',
	'LSBBB_NM'					=> 'NM',
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
	'LSBBB_SALE_DATE'			=> 'Sale Date',
	'LSBBB_SALE_HISTORY'		=> 'Sale History',
	'LSBBB_RETURN_ITEM'			=> 'Return Item',
	'LSBBB_RETURN_CONFIRM'		=> 'Do you really want to return this item?',

	'LSBBB_SKILL'				=> 'Skill',
	'LSBBB_LURE'				=> 'Lure',
	'LSBBB_RECIPE'				=> 'Recipe',
	'LSBBB_TOTAL_RECIPES'		=> 'Total Recipes',
	'LSBBB_BATTLEFIELD'			=> 'Battlefield',
	'LSBBB_CRYSTAL'				=> 'Crystal',
	'LSBBB_INGREDIENTS'			=> 'Ingredients',
	'LSBBB_RESULTS'				=> 'Results',
	'LSBBB_STEAL'				=> 'Steal',

	'LSBBB_INSTALL_GUIDES'		=> 'Installation Guides',
	'LSBBB_WINDOWS'				=> 'Windows',
	'LSBBB_LINUX'				=> 'Linux',
	'LSBBB_STEAMDECK'			=> 'Steam Deck',
	'LSBBB_INSTALL'				=> 'How to connect',

	'LSBBB_FFXICLOPEDIA'		=> 'FFXIclopedia',
	'LSBBB_FFXIAH'				=> 'FFXIAH',

	'LSBBB_VANADIEL'			=> "Vana'diel",
	'LSBBB_VANADIEL_WORLD_MAP'	=> "Vana'diel World Map",
	'LSBBB_MOBGROUP'			=> 'Mob',
	'LSBBB_CHARACTER'			=> 'Character',
	'LSBBB_CHARACTERNOTFOUND'	=> 'Character Not Found',
	'LSBBB_ACCOUNT'				=> 'Account',
	'LSBBB_LASTZONE'			=> 'Last Zone',
	'LSBBB_SERVERSTATS'			=> 'Server Stats',
	'LSBBB_STATRACE'			=> 'Race Distribution',
	'LSBBB_STATNATION'			=> 'Home Nation Distribution',
	'LSBBB_STATJOB'				=> 'Job Distribution',
	'LSBBB_STATGIL'				=> 'gil possessed',
	'LSBBB_STATFORSALE'			=> 'items for sale',
	'LSBBB_STATDISTANCE'		=> 'distance travelled',
	'LSBBB_STATBATTLES'			=> 'battles fought',
	'LSBBB_STATENEMIESDEFEATED'	=> 'enemies vanquished',
	'LSBBB_STATKNOCKOUTS'		=> 'adventurer K.O.s',
	'LSBBB_STATSPELLS'			=> 'spells cast',

	'LSBBB_SANDORIA'			=> "San d'Oria",
	'LSBBB_BASTOK'				=> 'Bastok',
	'LSBBB_WINDURST'			=> 'Windurst',

	'LSBBB_HUME'				=> 'Hume',
	'LSBBB_ELVAAN'				=> 'Elvaan',
	'LSBBB_TARUTARU'			=> 'Tarutaru',
	'LSBBB_MITRAH'				=> 'Mithra',
	'LSBBB_GALKA'				=> 'Galka',

    'LSBBB_CRAFT'               => 'Crafting',
	'LSBBB_FISH'				=> 'Fishing',
	'LSBBB_WOOD'			    => 'Woodworking',
	'LSBBB_SMITH'			    => 'Smithing',
	'LSBBB_GOLD'		        => 'Goldsmithing',
	'LSBBB_CLOTH'			    => 'Clothcraft',
	'LSBBB_LEATHER'             => 'Leatherworking',
	'LSBBB_BONE'			    => 'Bonecraft',
	'LSBBB_ALCHEMY'				=> 'Alchemy',
	'LSBBB_COOK'				=> 'Cooking',

	'LSBBB_FSH'					=> 'FSH',
	'LSBBB_CRP'					=> 'CRP',
	'LSBBB_BSM'					=> 'BSM',
	'LSBBB_GSM'					=> 'GSM',
	'LSBBB_WVR'					=> 'WVR',
	'LSBBB_LTW'					=> 'LTW',
	'LSBBB_BNC'					=> 'BNC',
	'LSBBB_ALC'					=> 'ALC',
	'LSBBB_CUL'					=> 'CUL',

	'LSBBB_WAR'					=> 'WAR',
	'LSBBB_MNK'					=> 'MNK',
	'LSBBB_WHM'					=> 'WHM',
	'LSBBB_BLM'					=> 'BLM',
	'LSBBB_RDM'					=> 'RDM',
	'LSBBB_THF'					=> 'THF',
	'LSBBB_PLD'					=> 'PLD',
	'LSBBB_DRK'					=> 'DRK',
	'LSBBB_BST'					=> 'BST',
	'LSBBB_BRD'					=> 'BRD',
	'LSBBB_RNG'					=> 'RNG',
	'LSBBB_SAM'					=> 'SAM',
	'LSBBB_NIN'					=> 'NIN',
	'LSBBB_DRG'					=> 'DRG',
	'LSBBB_SMN'					=> 'SMN',
	'LSBBB_BLU'					=> 'BLU',
	'LSBBB_COR'					=> 'COR',
	'LSBBB_PUP'					=> 'PUP',
	'LSBBB_SCH'					=> 'SCH',
	'LSBBB_DNC'					=> 'DNC',
	'LSBBB_GEO'					=> 'GEO',
	'LSBBB_RUN'					=> 'RUN',

	'LSBBB_NAV_INDEX'			=> 'FFXI',
	'LSBBB_NAV_INSTALL'			=> 'Install',
	'LSBBB_NAV_WORLDMAP'		=> 'World Map',
	'LSBBB_NAV_AUCTIONHOUSE'	=> 'Auction House',
	'LSBBB_NAV_CHARACTERS'		=> 'Characters',
	'LSBBB_NAV_MYLISTINGS'		=> 'My Listings',
	'LSBBB_NAV_CRAFTING'		=> 'Crafting',

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
