<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

/**
 * auction house functions
 */
class functions_ah
{

	// Get the auction history for the given itemid
	public function xi_auction_history($item_id = 2553, $limit = 25)
	{
		global $db;
		$return = array();

		if (is_numeric($item_id)) {
			$sql_array = array(
				'SELECT'	=> 'ah.itemid, ah.stack, ah.seller_name, ah.buyer_name, ah.sell_date, FORMAT(ah.sale, 0) sale,
					ib.name, ib.sortname, ib.stacksize,
					ahc.name category, ahc.id catid',
				'FROM'		=> array(
					'xidb.auction_house' => 'ah',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib'),
						'ON'	=> 'ah.itemid = ib.itemid',
					),
					array(
						'FROM'	=> array('xidb.auction_house_categories' => 'ahc'),
						'ON'	=> 'ib.ah = ahc.id',
					),
				),
				'WHERE'		=> 'ah.itemid = ' . (int) $item_id . '
					AND ah.sell_date > 0',
				'ORDER_BY'	=> 'sell_date DESC',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);

			$result = $db->sql_query_limit($sql, $limit);
			$ahdata = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);


			if (sizeof($ahdata) > 0) {
				foreach ($ahdata as $row) {
					$return[] = array(
						'name' => ucwords(str_replace("_", " ", $row['name']), "\t\r\n\f\v- "),
						'sortname' => ucwords(str_replace("_", " ", $row['sortname']), "\t\r\n\f\v- "),
						'itemid' => $row['itemid'],
						'stack' => $row['stack'],
						'stacksize' => $row['stacksize'],
						'sell_date' => gmdate("Y-m-d", $row['sell_date']),
						'sale' => $row['sale'],
						'seller_name' => $row['seller_name'],
						'buyer_name' => $row['buyer_name'],
						'category' => $row['category'],
						'catid' => $row['catid']
					);
				}
			}
		}
		return $return;
	}

	// FFXI Auction House Category
	public function xi_ah_category($category = 1, $hide = true)
	{
		global $db;
		if (is_numeric($category)) {
			$sql_array = array(
				'SELECT'	=> 'ib.itemid,
					ah.stack, AVG(IF(ah.sell_date = 0,ah.price,NULL)) avgprice,
					ib.stacksize, COUNT(IF(ah.sell_date = 0,1,NULL)) icount, ib.name, ib.sortname,
					ahc.name category, ahc.parent, ahc.id catid,
					ie.level, ie.jobs',
				'FROM'		=> array(
					'xidb.item_basic' => 'ib',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.auction_house' => 'ah'),
						'ON'	=> 'ib.itemid = ah.itemid',
					),
					array(
						'FROM'	=> array('xidb.auction_house_categories' => 'ahc'),
						'ON'	=> 'ib.ah = ahc.id',
					),
					array(
						'FROM'	=> array('xidb.item_equipment' => 'ie'),
						'ON'	=> 'ib.itemid = ie.itemid',
					),
				),
				'WHERE'		=> 'ahc.id = ' . (int) $category . '
					AND ib.ah > 0',
				'GROUP_BY'	=> 'ib.itemid, ah.stack',
				'ORDER_BY'	=> 'ahc.parent, ie.level DESC, ib.name',
			);
			if ($hide) {
				$sql_array['HAVING'] = 'COUNT(IF(ah.sell_date = 0,1,NULL)) > 0';
			}
			$sql = $db->sql_build_query('SELECT', $sql_array);

			$result = $db->sql_query($sql);
			$ahdata = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
		}
		return $ahdata;
	}

	public function xi_get_listings($item_id, $limit = 10)
	{
		global $db;
		if (is_numeric($item_id)) {
			$sql_array = array(
				'SELECT'	=> 'ah.itemid, ah.stack, ROUND(AVG(ah.price), 0) avgprice,
					ib.name, ib.sortname, ib.stacksize,
					ahc.name category, ahc.id catid,
					ie.level, COUNT(IF(ah.sell_date = 0,1,NULL)) icount',
				'FROM'		=> array(
					'xidb.auction_house' => 'ah',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib'),
						'ON'	=> 'ah.itemid = ib.itemid',
					),
					array(
						'FROM'	=> array('xidb.item_equipment' => 'ie'),
						'ON'	=> 'ah.itemid = ie.itemid',
					),
					array(
						'FROM'	=> array('xidb.auction_house_categories' => 'ahc'),
						'ON'	=> 'ib.ah = ahc.id',
					),
				),
				'WHERE'		=> 'ah.itemid = ' . (int) $item_id,
				'GROUP_BY'	=> 'ib.itemid, ah.stack',
				'ORDER_BY'	=> 'ah.sell_date DESC',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);

			$result = $db->sql_query_limit($sql, $limit);
			$ahdata = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			$return = array();

			if (sizeof($ahdata) > 0) {
				foreach ($ahdata as $row) {
					$return[] = array(
						'name' => ucwords(str_replace("_", " ", $row['name']), "\t\r\n\f\v- "),
						'sortname' => ucwords(str_replace("_", " ", $row['sortname']), "\t\r\n\f\v- "),
						'itemid' => $row['itemid'],
						'stack' => $row['stack'],
						'stacksize' => $row['stacksize'],
						'avgprice' => $row['avgprice'],
						'level' => $row['level'],
						'icount' => $row['icount'],
						'category' => $row['category'],
						'catid' => $row['catid']
					);
				}
			}
		}
		return $return;
	}

	// Get the latest items added to the Auction House
	public function xi_ah_latest($limit = 30)
	{
		global $db;
		$ah_data = array();

		$sql_array = array(
			'SELECT'	=> 'ah.itemid, ah.stack,
				ib.name, ib.sortname, ib.stacksize,
				ROUND(AVG(ah.price), 0) avgprice, ahc.name category, ahc.id catid,
				"-" icount, "Latest Unsold Listings" parent',
			'FROM'		=> array(
				'xidb.auction_house' => 'ah',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array('xidb.item_basic' => 'ib'),
					'ON'	=> 'ib.itemid = ah.itemid',
				),
				array(
					'FROM'	=> array('xidb.auction_house_categories' => 'ahc'),
					'ON'	=> 'ib.ah = ahc.id',
				),
			),
			'WHERE'		=> 'ah.date < 4070908800
				AND ah.sell_date = 0',
			'GROUP_BY'	=> 'ah.itemid',
			'ORDER_BY'	=> 'ah.date DESC',
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query_limit($sql, $limit);

		$ah_data = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		return $ah_data;
	}

	public function xi_ah_mine($user)
	{
		global $db;
		$chars_sql = <<<SQL
	
	    SELECT c.charname
	    FROM xidb.chars c, xidb.accounts a
	    WHERE a.registration_email = "{$user['user_email']}"
	    AND a.id = c.accid
	
	SQL;
		$result = $db->sql_query($chars_sql);
		$chars_results = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$chars_array = array();
		foreach ($chars_results as $char) {
			$chars_array[] = $char['charname'];
		}
		$char_names = '"' . implode('", "', $chars_array) . '"';

		$sql = <<<SQL
	
	SELECT ah.id ahid, i.itemid, ah.stack, FORMAT(ah.price, 0) price, AVG(ah.price) avgprice, ah.seller_name, i.stacksize, i.name, i.sortname, ahc.name category, ahc.parent, ahc.id catid, ie.level, ie.jobs
	FROM xidb.item_basic i
	LEFT JOIN xidb.auction_house ah ON i.itemid = ah.itemid
	LEFT JOIN xidb.auction_house_categories ahc ON i.aH = ahc.id
	LEFT JOIN xidb.item_equipment ie ON i.itemid = ie.itemid
	WHERE ah.seller_name IN ({$char_names})
	AND ah.sale = 0
	GROUP BY ahid
	
	SQL;

		$result = $db->sql_query($sql);
		$ahdata = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		return $ahdata;
	}

	// Display the Auction House Categories
	public function xi_ah_categories()
	{
		global $db;
		$sql = "SELECT * FROM xidb.auction_house_categories ORDER BY parent, name";
		$result = $db->sql_query($sql);
		$catdata = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $catdata;
	}

	public function xi_ah_returnitem($ahid)
	{
		global $db, $user;

		$sql_array = array(
			'SELECT'	=> 'a.registration_email, ah.*, ib.*',
			'FROM'		=> array(
				'xidb.auction_house' => 'ah',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array('xidb.item_basic' => 'ib'),
					'ON'	=> 'ib.itemid = ah.itemid',
				),
				array(
					'FROM'	=> array('xidb.chars' => 'c'),
					'ON'	=> 'c.charid = ah.seller',
				),
				array(
					'FROM'	=> array('xidb.accounts' => 'a'),
					'ON'	=> 'a.id = c.accid',
				),
			),
			'WHERE'		=> 'ah.id = ' . (int) $ahid . '
				AND ah.sale = 0',
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query($sql);
		$listing_data = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		// check if the person logged in is the person who listed the item
		if ($listing_data['registration_email'] == $user->data['user_email']) {
			if ($listing_data['stack'] == 1) {
				$quantity = $listing_data['stackSize'];
			} else {
				$quantity = 1;
			}

			$insert_data = [
				'charid'	=> (int) $listing_data['seller'],
				'charname'	=> $listing_data['seller_name'],
				'box'		=> 1,
				'slot'		=> 0,
				'itemid'	=> (int) $listing_data['itemid'],
				'itemsubid'	=> 0,
				'quantity'	=> (int) $quantity,
				'extra' 	=> NULL,
				'senderid'	=> 0,
				'sender'	=> $listing_data['seller_name'],
				'received'	=> 0,
				'sent'		=> 0,
			];
			$delivery_sql = 'INSERT INTO xidb.delivery_box ' . $db->sql_build_array('INSERT', $insert_data);
			$result = $db->sql_query($delivery_sql);
			$db->sql_freeresult($result);

			$remove_sql = "DELETE FROM xidb.auction_house WHERE id = {$listing_data['id']}";
			$result = $db->sql_query($remove_sql);
			$db->sql_freeresult($result);
		} else {
			die("How did you get here? Hacking attempt?");
		}
	}
}
