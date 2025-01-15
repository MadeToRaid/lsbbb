<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

/**
 * fish functions
 */
class functions_fish
{
	public function xi_fish_info($item_id)
	{
		global $db;
		$fish_data = array();
		$return = array();
		if (is_numeric($item_id)) {
			$sql_array = array(
				'SELECT'	=> 'fa.name area_name, zs.name zone_name, fc.*, fg.*, ff.*, GROUP_CONCAT(fl.name) lure_names, GROUP_CONCAT(fl.lureid) lureids',
				'FROM'		=> array(
					'xidb.fishing_area' => 'fa',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.zone_settings' => 'zs'),
						'ON'	=> 'fa.zoneid = zs.zoneid',
					),
					array(
						'FROM'	=> array('xidb.fishing_catch' => 'fc'),
						'ON'	=> 'fc.zoneid = fa.zoneid AND fc.areaid = fa.areaid',
					),
					array(
						'FROM'	=> array('xidb.fishing_group' => 'fg'),
						'ON'	=> 'fg.groupid = fc.groupid',
					),
					array(
						'FROM'	=> array('xidb.fishing_fish' => 'ff'),
						'ON'	=> 'ff.fishid = fg.fishid',
					),
					array(
						'FROM'	=> array('xidb.fishing_lure' => 'fl'),
						'ON'	=> 'fl.fishid = ff.fishid',
					),
				),
				'WHERE'		=> 'ff.fishid = ' . (int) $item_id,
				'GROUP_BY'	=> 'fa.zoneid, fa.areaid, ff.fishid',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);

			$result = $db->sql_query($sql);
			$fish_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($fish_data as $fkey => $fish) {
				$lure_data = array();
				$lure_names = explode(',', $fish['lure_names']);
				$lureids = explode(',', $fish['lureids']);
				foreach ($lureids as $key => $value) {
					$lure_data[] = array('lureid' => $lureids[$key], 'lure_name' => $lure_names[$key]);
				}
				$return[] = array(
					'area_name' => $fish['area_name'],
					'zone_name' => str_replace('_', ' ', $fish['zone_name']),
					'zoneid' => $fish['zoneid'],
					'areaid' => $fish['areaid'],
					'skill_level' => $fish['skill_level'],
					'difficulty' => $fish['difficulty'],
					'lures' => $lure_data,
					'rownum' => $fkey
				);
			}
		}
		return $return;
	}

	public function xi_fish_by_zone($zone_id)
	{
		global $db;
		$return = array();
		$fish_data = array();
		if (is_numeric($zone_id)) {
			$sql_array = array(
				'SELECT'	=> 'fa.*, zs.name zone_name, fc.*, fg.*, ff.*',
				'FROM'		=> array(
					'xidb.fishing_area' => 'fa',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.zone_settings' => 'zs'),
						'ON'	=> 'fa.zoneid = zs.zoneid',
					),
					array(
						'FROM'	=> array('xidb.fishing_catch' => 'fc'),
						'ON'	=> 'fc.zoneid = fa.zoneid AND fc.areaid = fa.areaid',
					),
					array(
						'FROM'	=> array('xidb.fishing_group' => 'fg'),
						'ON'	=> 'fg.groupid = fc.groupid',
					),
					array(
						'FROM'	=> array('xidb.fishing_fish' => 'ff'),
						'ON'	=> 'ff.fishid = fg.fishid',
					),
				),
				'WHERE'		=> 'fa.fishid = ' . (int) $zone_id,
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$fish_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			$return = $fish_data;
		}
		return $return;
	}
}
