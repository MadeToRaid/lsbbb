<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

use madetoraid\lsbbb\includes\functions_xi;

/**
 * item functions
 */
class functions_item
{
	public function __construct()
	{
		$this->xi	= new functions_xi();
	}

	public function xi_get_item($item_id)
	{
		global $db;
		$return = array();
		if (is_numeric($item_id)) {
			$sql_array = array(
				'SELECT'	=> 'ib.itemid, ib.name, ib.sortname, BIN(ib.flags) flags, ib.stacksize, ib.basesell,
					ahc.name category, ahc.parent, ahc.id category_id,
					ah.stack, AVG(IF(ah.sell_date = 0,ah.price,NULL)) avgprice,
					COUNT(IF(ah.sell_date = 0,1,NULL)) for_sale',
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
				),
				'WHERE'		=> 'ib.itemid = ' . (int) $item_id,
				'GROUP_BY'	=> 'ib.itemid, ah.stack',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);


			$result = $db->sql_query($sql);
			$item_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($item_data as $item) {
				$return[] = array(
					'itemid' => $item['itemid'],
					'name' => $this->xi->xi_ucwords($item['name']),
					'sortname' => $this->xi->xi_ucwords($item['sortname']),
					'flags' => $item['flags'],
					'parent' => $item['parent'],
					'category' => $item['category'],
					'category_id' => $item['category_id'],
					'stack' => $item['stack'],
					'stacksize' => $item['stacksize'],
					'for_sale' => number_format($item['for_sale']),
					'avgprice' => number_format($item['avgprice']),
					'basesell' => number_format($item['basesell'])
				);
			}
		}
		return $return;
	}

	public function xi_item_search($search = "Kraken")
	{
		if (strlen($search) < 4) {
			return array();
		}
		global $db;
		$search = htmlspecialchars_decode($search);
		$search = str_replace(" ", "_", $search);
		$sql_array = array(
			'SELECT'	=> 'ib.itemid, ib.name, ib.sortname, BIN(ib.flags) flags, ib.stacksize, ib.basesell,
				ahc.name category, ahc.parent, ahc.id category_id,
				ah.stack, AVG(IF(ah.sell_date = 0,ah.price,NULL)) avgprice,
				COUNT(IF(ah.sell_date = 0,1,NULL)) for_sale',
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
			),
			'WHERE'		=> 'ib.name COLLATE UTF8MB4_GENERAL_CI ' . $db->sql_like_expression($db->get_any_char() . utf8_clean_string($search) . $db->get_any_char()),
			'GROUP_BY'	=> 'ib.itemid, ah.stack',
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query($sql);
		$item_data = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		//return $item_data;
		$return = array();
		foreach ($item_data as $item) {
			$return[] = array(
				'itemid' => $item['itemid'],
				'name' => $this->xi->xi_ucwords($item['name']),
				'sortname' => $this->xi->xi_ucwords($item['sortname']),
				'flags' => $item['flags'],
				'parent' => $item['parent'],
				'category' => $item['category'],
				'category_id' => $item['category_id'],
				'stack' => $item['stack'],
				'stacksize' => $item['stacksize'],
				'for_sale' => number_format($item['for_sale']),
				'avgprice' => number_format($item['avgprice']),
				'basesell' => number_format($item['basesell'])
			);
		}
		return $return;
	}

	public function xi_drop_search($item_id)
	{
		global $db;
		$return = array();
		if (is_numeric($item_id)) {
			$sql_array = array(
				'SELECT'	=> 'REPLACE(ib.name,"_", " ") item_name, ib.sortname, mg.name mob_name, z.zoneid, z.name zone_name, md.itemRate rate, mg.groupid, mg.minLevel, mg.maxLevel, ib.itemid',
				'FROM'		=> array(
					'xidb.item_basic' => 'ib',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.mob_droplist' => 'md'),
						'ON'	=> 'ib.itemid = md.itemid',
					),
					array(
						'FROM'	=> array('xidb.mob_groups' => 'mg'),
						'ON'	=> 'md.dropId = mg.dropId',
					),
					array(
						'FROM'	=> array('xidb.zone_settings' => 'z'),
						'ON'	=> 'z.zoneid = mg.zoneid',
					),
				),
				'WHERE'		=> 'md.itemRate IS NOT NULL
					AND md.itemRate > 0
					AND mg.maxlevel > 0
					AND ib.itemid = ' . (int) $item_id,
				'ORDER_BY'	=> 'z.name,rate DESC,ib.name,mg.name',
			);

			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$itemdata = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			foreach ($itemdata as $key => $item) {
				$return[] = array(
					'item_name' => $this->xi->xi_ucwords($item['item_name']),
					'sortname' => $this->xi->xi_ucwords($item['sortname']),
					'mob_name' => $this->xi->xi_ucwords($item['mob_name']),
					'zoneid' => $item['zoneid'],
					'zone_name' => str_replace('_', ' ', $item['zone_name']),
					'minLevel' => $item['minLevel'],
					'maxLevel' => $item['maxLevel'],
					'itemid' => $item['itemid'],
					'groupid' => $item['groupid'],
					'rate' => $item['rate'] / 10,
					'rownum' => $key,
				);
			}
		}
		return $return;
	}
}
