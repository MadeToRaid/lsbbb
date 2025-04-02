<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

/**
 * job functions
 */
class functions_job
{
	public $jobs = array(
		 1 => 'WAR',
		 2 => 'MNK',
		 3 => 'WHM',
		 4 => 'BLM',
		 5 => 'RDM',
		 6 => 'THF',
		 7 => 'PLD',
		 8 => 'DRK',
		 9 => 'BST',
		10 => 'BRD',
		11 => 'RNG',
		12 => 'SAM',
		13 => 'NIN',
		14 => 'DRG',
		15 => 'SMN',
		16 => 'BLU',
		17 => 'COR',
		18 => 'PUP',
		19 => 'DNC',
		20 => 'SCH',
		21 => 'GEO',
		22 => 'RUN',
		23 => 'MON',
	);

	public function xi_job_abilities($job_id)
	{
		global $db;
		$xi = new functions_xi();
		$return = array();
		$ability_data = array();

		if (is_numeric($job_id)) {
			$sql_array = array(
				'SELECT'	=> 'a.abilityid, a.name, a.level, a.validtarget, a.casttime, a.recasttime, a.range, a.isaoe, a.content_tag',
				'FROM'		=> array(
					'xidb.abilities' => 'a',
				),
				//'LEFT_JOIN'	=> array(
				//	array(
				//		'FROM'	=> array('xidb.item_basic' => 'ib'),
				//		'ON'	=> 'ib.sortname = sl.name',
				//	),
				//),
				'WHERE'		=> 'a.level <= 75 AND
								a.job = ' . (int) $job_id,
				'ORDER_BY'	=> 'a.level',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$ability_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($ability_data as $key => $ability) {
				if($ability['recasttime'] >= 3600){
					$recasttime = gmdate("G:i:s", $ability['recasttime']);
				}
				else {
					$recasttime = gmdate("i:s", $ability['recasttime']);
				}
				$return[] = array(
					'abilityid'		=> (int) $ability['abilityid'],
					'name'			=> $xi->xi_ucwords($ability['name']),
					'level'			=> (int) $ability['level'],
					'validtarget'	=> $ability['validtarget'],
					'casttime'		=> (int) $ability['casttime'],
					'recasttime'	=> $recasttime,
					'range'			=> (int) $ability['range'],
					'isaoe'			=> (int) $ability['isaoe'],
					'content_tag'	=> $ability['content_tag'],
					'rownum'		=> $key,
				);
			}
		}
		return $return;
	}

	public function xi_job_spells($job_id)
	{
		global $db;
		$xi = new functions_xi();
		$return = array();
		$spell_data = array();

		if (is_numeric($job_id)) {
			$sql_array = array(
				'SELECT'	=> 'ib.itemid, sl.name, sl.content_tag, CAST(CONV(HEX(SUBSTRING(sl.jobs, '. (int) $job_id .', 1)), 16, 10) AS UNSIGNED) level, sl.group, sl.family, sl.element, sl.validtargets, sl.skill, sl.mpcost, sl.casttime, sl.recasttime, sl.aoe, sl.spell_range, sl.content_tag',
				'FROM'		=> array(
					'xidb.spell_list' => 'sl',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib'),
						'ON'	=> 'ib.sortname = sl.name',
					),
				),
				'WHERE'		=> 'CONV(HEX(SUBSTRING(sl.jobs, '. (int) $job_id .', 1)), 16, 10) > 0
								AND CONV(HEX(SUBSTRING(sl.jobs, '. (int) $job_id .', 1)), 16, 10) <= 75
								AND sl.group != 8',
				'ORDER_BY'	=> 'level, ib.sortname',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$spell_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($spell_data as $key => $spell) {
				$return[] = array(
					'itemid'		=> $spell['itemid'],
					'name'			=> $xi->xi_ucwords($spell['name']),
					'level'			=> $spell['level'],
					'group'			=> $spell['group'],
					'family'		=> $spell['family'],
					'element'		=> $spell['element'],
					'validtargets'	=> $spell['validtargets'],
					'skill'			=> $spell['skill'],
					'mpcost'		=> $spell['mpcost'],
					'casttime'		=> $spell['casttime']/1000,
					'recasttime'	=> $spell['recasttime']/1000,
					'aoe'			=> $spell['aoe'],
					'range'			=> $spell['spell_range']/10,
					'content_tag'	=> $spell['content_tag'],
					'rownum'		=> $key,
				);
			}
		}
		return $return;
	}

	public function xi_job_traits($job_id)
	{
		global $db;
		$xi = new functions_xi();
		$return = array();
		$trait_data = array();

		if (is_numeric($job_id)) {
			$sql_array = array(
				'SELECT'	=> 't.traitid, t.name, t.level, t.rank, t.content_tag',
				'FROM'		=> array(
					'xidb.traits' => 't',
				),
				'WHERE'		=> 't.job = ' . (int) $job_id . '
								AND t.level <= 75',
				'ORDER_BY'	=> 't.level',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$trait_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($trait_data as $key => $trait) {
				$return[] = array(
					'traitid'		=> (int) $trait['traitid'],
					'name'			=> $xi->xi_ucwords($trait['name']),
					'level'			=> (int) $trait['level'],
					'rank'			=> (int) $trait['rank'],
					'content_tag'	=> $trait['content_tag'],
					'rownum'		=> $key,
				);
			}
		}
		return $return;
	}
}