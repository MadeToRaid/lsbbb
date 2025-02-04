<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

/**
 * zone functions
 */
class functions_zone
{
	public $region_names = array(
		0 => "RONFAURE",
		1 => "ZULKHEIM",
		2 => "NORVALLEN",
		3 => "GUSTABERG",
		4 => "DERFLAND",
		5 => "SARUTABARUTA",
		6 => "KOLSHUSHU",
		7 => "ARAGONEU",
		8 => "FAUREGANDI",
		9 => "VALDEAUNIA",
		10 => "QUFIMISLAND",
		11 => "LITELOR",
		12 => "KUZOTZ",
		13 => "VOLLBOW",
		14 => "ELSHIMOLOWLANDS",
		15 => "ELSHIMOUPLANDS",
		16 => "TULIA",
		17 => "MOVALPOLOS",
		18 => "TAVNAZIA",
		19 => "SANDORIA",
		20 => "BASTOK",
		21 => "WINDURST",
		22 => "JEUNO",
		23 => "DYNAMIS",
		24 => "TAVNAZIAN_MARQ",
		25 => "PROMYVION",
		26 => "LUMORIA",
		27 => "LIMBUS",
		28 => "WEST_AHT_URHGAN",
		29 => "MAMOOL_JA_SAVAGE",
		30 => "HALVUNG",
		31 => "ARRAPAGO",
		32 => "ALZADAAL",
		33 => "RONFAURE_FRONT",
		34 => "NORVALLEN_FRONT",
		35 => "GUSTABERG_FRONT",
		36 => "DERFLAND_FRONT",
		37 => "SARUTA_FRONT",
		38 => "ARAGONEAU_FRONT",
		39 => "FAUREGANDI_FRONT",
		40 => "VALDEAUNIA_FRONT",
		41 => "ABYSSEA",
		42 => "THE_THRESHOLD",
		43 => "ABDHALJS",
		44 => "ADOULIN_ISLANDS",
		45 => "EAST_ULBUKA",
		255 => "UNKNOWN",
	);

	public function xi_get_all_zones()
	{
		global $db;
		$zone_data = array();
		$sql = <<<SQL
	SELECT * FROM xidb.zone_settings zs
	WHERE zoneid > 0
	SQL;
		$result = $db->sql_query($sql);
		$zone_data = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$return = array();
		foreach ($zone_data as $zone) {
			$return[] = array(
				'zoneid' => $zone['zoneid'],
				'zonetype' => $zone['zonetype'],
				'zone_name' => str_replace('_', ' ', $zone['name']),
			);
		}
		return $return;
	}

	public function xi_zone_info($zone_id)
	{
		global $db;
		$zone_data = array();
		$return = array();
		if (is_numeric($zone_id)) {
			$sql_array = array(
				'SELECT'	=> 'zs.zoneid, zs.zonetype, zs.name',
				'FROM'		=> array(
					'xidb.zone_settings' => 'zs',
				),
				'WHERE'		=> 'zs.zoneid = ' . (int) $zone_id,
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$zone_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($zone_data as $zone) {
				$return[] = array(
					'zoneid' => $zone['zoneid'],
					'zonetype' => $zone['zonetype'],
					'zone_name' => str_replace('_', ' ', $zone['name'])
				);
			}
		}
		return $return;
	}

	public function xi_zone_bcnm_info($zone_id)
	{
		global $db;
		$xi = new functions_xi();
		$return = array();
		$bcnm_data = array();

		if (is_numeric($zone_id)) {
			$sql_array = array(
				'SELECT'	=> 'br.bcnmid, br.name, br.fastestname, br.fastestpartysize, br.fastesttime',
				'FROM'		=> array(
					'xidb.bcnm_records' => 'br',
				),
				'WHERE'		=> 'br.zoneid = ' . (int) $zone_id,
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$bcnm_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($bcnm_data as $bcnm) {
				$return[] = array(
					'bcnmid'		=> (int) $bcnm['bcnmid'],
					'name'			=> $xi->xi_ucwords($bcnm['name']),
					'fastestname'		=> $bcnm['fastestname'],
					'fastestpartysize'	=> (int) $bcnm['fastestpartysize'],
					'fastesttime'		=> gmdate("i:s", $bcnm['fastesttime']),
				);
			}
		}
		return $return;
	}

	public function xi_zone_links($zone_id)
	{
		global $db;
		$return = array();
		$zone_data = array();

		if (is_numeric($zone_id)) {
			$sql_array = array(
				'SELECT'	=> 'zs1.zoneid, zs1.name zone_name, zs2.zoneid tozoneid, zs2.name to_zone_name',
				'FROM'		=> array(
					'xidb.zonelines' => 'zl',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.zone_settings' => 'zs1'),
						'ON'	=> 'zs1.zoneid = zl.fromzone',
					),
					array(
						'FROM'	=> array('xidb.zone_settings' => 'zs2'),
						'ON'	=> 'zs2.zoneid = zl.tozone',
					),
				),
				'WHERE'		=> 'zl.fromzone = ' . (int) $zone_id . '
					AND	zl.tozone > 0',
				'GROUP_BY'	=> 'zl.tozone',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$link_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($link_data as $link) {
				$return[] = array(
					'zoneid' => $link['zoneid'],
					'zone_name' => str_replace('_', ' ', $link['zone_name']),
					'tozoneid' => $link['tozoneid'],
					'to_zone_name' => str_replace('_', ' ', $link['to_zone_name']),
				);
			}
		}
		return $return;
	}

	public function xi_zone_maps($zone_name, $root_path = "./")
	{
		$all_map_files = scandir($root_path . 'ext/madetoraid/lsbbb/styles/all/theme/images/maps/1024/');
		$map_files = array();
		$zone_name_lower = strtolower(str_replace(' ', '_', $zone_name));
		$zone_name_lower = str_replace('[', '', $zone_name_lower);
		$zone_name_lower = str_replace(']', '', $zone_name_lower);
		$num = 1;
		foreach ($all_map_files as $file) {
			if ($zone_name_lower == substr($file, 0, strlen($zone_name_lower)) && (is_numeric(substr($file, strlen($zone_name_lower) + 1, 1)) || substr($file, strlen($zone_name_lower) + 1, 3) == "png")) {
				$map_files[] = array(
					'num' => $num,
					'filename' => $file,
				);
				$num++;
			}
		}
		return $map_files;
	}

	public function xi_zone_mobs($zone_id)
	{
		global $db;
		$return = array();
		$zone_mob_data = array();
		if (is_numeric($zone_id)) {
			$sql_array = array(
				'SELECT'	=> 'zs.zoneid, zs.name zone_name, mfs.familyid, mfs.family family_name, mfs.superfamilyid, mfs.superfamily super_family_name,
					mp.poolid, mp.name pool_name, mp.aggro, mp.true_detection, mp.links, mp.mobtype, mp.immunity,
					mg.groupid, mg.name group_name, mg.respawntime, mg.minlevel, mg.maxlevel',
				'FROM'		=> array(
					'xidb.mob_family_system' => 'mfs',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.mob_pools' => 'mp'),
						'ON'	=> 'mp.familyid = mfs.familyid',
					),
					array(
						'FROM'	=> array('xidb.mob_groups' => 'mg'),
						'ON'	=> 'mg.poolid = mp.poolid',
					),
					array(
						'FROM'	=> array('xidb.zone_settings' => 'zs'),
						'ON'	=> 'zs.zoneid = mg.zoneid',
					),
				),
				'WHERE'		=> 'zs.zoneid = ' . (int) $zone_id . '
					AND mg.maxlevel > 0',
				'ORDER_BY'	=> 'mg.minlevel ASC',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$zone_mob_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($zone_mob_data as $key => $mob_data) {
				$return[] = array(
					'zoneid' => $mob_data['zoneid'],
					'zone_name' => str_replace('_', ' ', $mob_data['zone_name']),
					'familyid' => $mob_data['familyid'],
					'family_name' => str_replace('_', ' ', $mob_data['family_name']),
					'superfamilyid' => $mob_data['superfamilyid'],
					'super_family_name' => str_replace('_', ' ', $mob_data['super_family_name']),
					'poolid' => $mob_data['poolid'],
					'pool_name' => str_replace('_', ' ', $mob_data['pool_name']),
					'aggro' => $mob_data['aggro'],
					'true_detection' => $mob_data['true_detection'],
					'links' => $mob_data['links'],
					'mobtype' => $mob_data['mobtype'],
					'immunity' => $mob_data['immunity'],
					'groupid' => $mob_data['groupid'],
					'group_name' => str_replace('_', ' ', $mob_data['group_name']),
					'respawntime' => $mob_data['respawntime'],
					'minlevel' => $mob_data['minlevel'],
					'maxlevel' => $mob_data['maxlevel'],
					'rownum' => $key,
				);
			}
		}
		return $return;
	}

	public function xi_conquest_info()
	{
		global $db;
		$zone_mob_data = array();
		$sql = <<<SQL
	
	SELECT * FROM xidb.conquest_system;
	
	SQL;
		$result = $db->sql_query($sql);
		$conquest_data = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		foreach ($conquest_data as $key => $data) {
			$conquest_data[$key]['region_name'] = $this->region_names[$data['region_id']];
		}
		return $conquest_data;
	}

	function xi_zone_weather($zone_id)
	{
		global $db;
		$weather_cycle = 2160;
		$sql = "SELECT weather FROM xidb.zone_weather WHERE zone = 54";

		$result = $db->sql_query($sql);
		$weather_row = $db->sql_fetchrow($result);
		$weather_blob = $weather_row['weather'];
		$db->sql_freeresult($result);
		print_r($weather_blob);
		for ($i = 0; $i < $weather_cycle; $i++) {
			if ($weather_blob[$i]) {
				$w_normal = (int) $weather_blob[$i] >> 10;
				die("Normal Weather: " . $w_normal);
				$w_common = (int) ($weather_blob[$i] >> 5) & 0x1F;
				$w_rare   = (int) $weather_blob[$i] & 0x1F;
			}
		}
	}

	public function xi_zone_npcs($zoneid)
	{
		global $db;
		$xi = new functions_xi();
		$return = array();
		$npc_data = array();

		if (is_numeric($zoneid)) {
			$sql_array = array(
				'SELECT'	=> '*',
				'FROM'		=> array(
					'xidb.npc_list' => 'nl',
				),
				'WHERE'		=> 'nl.npcid > (16777216 + (' . (int) $zoneid  . ' * 4096)) and nl.npcid < (16777216 + (' . (int) ($zoneid + 1) . ' * 4096))
					AND nl.status = 0
					AND nl.name_prefix = 32
					AND nl.entityflags IN (27, 28, 29)
					AND (content_tag IN ("MADETORAID", "ZILART", "COP", "TOAU", "WOTG") OR content_tag is NULL)',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$npc_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			foreach ($npc_data as $key => $npc) {
				$return[] = array(
					'npcid'			=> (int) $npc['npcid'],
					'name'			=> $xi->xi_ucwords($npc['name']),
					'polutils_name'		=> $npc['polutils_name'],
					'status'		=> (int) $npc['status'],
					'pos_rot'		=> (int) $npc['pos_rot'],
					'pos_x'			=> (int) $npc['pos_x'],
					'pos_y'			=> (int) $npc['pos_y'],
					'pos_z'			=> (int) $npc['pos_z'],
					'entityflags'		=> (int) $npc['entityFlags'],
					'content_tag'		=> $npc['content_tag'],
					'rownum'		=> $key,
				);
			}
		}
		//print_r($npc_data);
		return $return;
	}
}
