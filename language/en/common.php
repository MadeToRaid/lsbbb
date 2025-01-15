<?php
/**
 *
 * PHPBB XI Server Integration. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, Ganiman, https://ganiman.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, [

	'LSBBB_HELLO'			=> 'Hello %s!',
	'LSBBB_GOODBYE'			=> 'Goodbye %s!',

	'LSBBB_EVENT'			=> ' :: lsbBB Event :: ',

	'ACP_LSBBB_GOODBYE'			=> 'Should say goodbye?',
	'ACP_LSBBB_SETTING_SAVED'		=> 'Settings have been saved successfully!',

	'LSBBB_NOTIFICATION'			=> 'lsbBB notification',

	'LSBBB_PAGE'				=> 'FFXI',
	'LSBBB_AH'				=> 'Auction House',
	'LSBBB_MY_LISTINGS'			=> 'My Listings',
	'LSBBB_CHARACTER'			=> 'Character',
	'LSBBB_CHARACTERS'			=> 'Characters',
	'LSBBB_WORLD_MAP'			=> 'World Map',
	'LSBBB_ZONE'				=> 'Zone',
	'LSBBB_CONNECTIONS'			=> 'Connections',
	'LSBBB_ITEM'				=> 'Item',
	'LSBBB_PARTY'				=> 'Party',
	'LSBBB_RECORD'				=> 'Record',
	'LSBBB_NPC'				=> 'NPC',
	'LSBBB_MOB'				=> 'Mob',
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

	'VIEWING_LSBBB'				=> 'Viewing lsbBB',
	'VIEWING_LSBBB_ITEM'			=> 'Viewing Item',
	'VIEWING_LSBBB_AH'			=> 'Viewing Auction House',
	'VIEWING_LSBBB_ZONE'			=> 'Viewing Zone',
	'VIEWING_LSBBB_MAP'			=> 'Viewing World Map',
	'VIEWING_LSBBB_MOB'			=> 'Viewing Mob',

]);
