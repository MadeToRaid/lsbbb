<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

/**
 * char functions
 */
class functions_char
{
	public $races = array(
		1 => 'Hm',
		2 => 'Hf',
		3 => 'Em',
		4 => 'Ef',
		5 => 'Tm',
		6 => 'Tf',
		7 => 'M',
		8 => 'G'
	);

	public $faces = array(
		0 => '1a',
		1 => '1b',
		2 => '2a',
		3 => '2b',
		4 => '3a',
		5 => '3b',
		6 => '4a',
		7 => '4b',
		8 => '5a',
		9 => '5b',
		10 => '6a',
		11 => '6b',
		12 => '7a',
		13 => '7b',
		14 => '8a',
		15 => '8b'
	);

	public $nations = array(
		0 => "San d'Oria",
		1 => "Bastok",
		2 => "Windurst"
	);

	public $jobs = array(
		1  => "WAR",
		2  => "MNK",
		3  => "WHM",
		4  => "BLM",
		5  => "RDM",
		6  => "THF",
		7  => "PLD",
		8  => "DRK",
		9  => "BST",
		10 => "BRD",
		11 => "RNG",
		12 => "SAM",
		13 => "NIN",
		14 => "DRG",
		15 => "SMN",
		16 => "BLU",
		17 => "COR",
		18 => "PUP",
		19 => "DNC",
		20 => "SCH"
	);

	public function xi_char_search()
	{
		global $db, $races, $faces, $nations, $jobs;

		$sql = <<<SQL
	
	SELECT pu.username, pu.user_id forumid, a.id accountid, c.charid, c.charname, c.nation, cl.face, cl.race, cp.rank_bastok, cp.rank_sandoria, cp.rank_windurst
	FROM xidb.chars c
	LEFT JOIN xidb.accounts a ON a.id = c.accid
	LEFT JOIN xidb.char_look cl ON cl.charid = c.charid
	LEFT JOIN xidb.char_profile cp ON cp.charid = c.charid
	LEFT JOIN forums.phpbb_users pu ON pu.user_email = a.registration_email
	WHERE c.accid > 0
	AND c.gmlevel = 0
	GROUP BY c.charname
	
	SQL;

		$result = $db->sql_query($sql);
		$chardata = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$return = array();
		foreach ($chardata as $char) {
			$return[] = array(
				'charid' => $char['charid'],
				'forumid' => $char['forumid'],
				'charname' => $char['charname'],
				'nation' => $this->nations[$char['nation']],
				'face' => $this->faces[$char['face']],
				'race' => $this->races[$char['race']],
				'rank_bastok' => $char['rank_bastok'],
				'rank_windurst' => $char['rank_windurst'],
				'rank_sandoria' => $char['rank_sandoria'],
				'char_image' => $this->races[$char['race']] . $this->faces[$char['face']] . '.jpg',
				'username' => $char['username']
			);
		}
		return $return;
	}

	public function xi_char_details($charid)
	{
		global $db, $user, $races, $faces, $nations, $jobs;

		$sql = <<<SQL
	SELECT c.charid, c.accid, c.charname, c.nation, c.pos_zone, c.playtime, c.gmlevel,
	cl.face, cl.race, cl.size,
	cp.rank_sandoria, cp.rank_bastok, cp.rank_windurst,
	cj.war, cj.mnk, cj.whm, cj.blm, cj.rdm, cj.thf, cj.pld, cj.drk, cj.bst, cj.brd, cj.rng, cj.sam, cj.nin, cj.drg, cj.smn, cj.blu, cj.cor, cj.pup, cj.dnc, cj.sch, cj.geo, cj.run,
	GROUP_CONCAT(DISTINCT cs2.skillid,"|",cs2.value) skills,
	cs.hp, cs.mp, cs.mjob, cs.sjob, cs.bazaar_message, cs.mlvl, cs.slvl, t.description title
	
	FROM xidb.chars c
	LEFT JOIN xidb.char_look cl ON cl.charid = c.charid
	LEFT JOIN xidb.char_profile cp ON cp.charid = c.charid
	LEFT JOIN xidb.char_equip ce on ce.charid = c.charid 
	LEFT JOIN xidb.char_jobs cj on cj.charid = c.charid
	LEFT JOIN xidb.char_stats cs ON cs.charid = c.charid
	LEFT JOIN xidb.char_skills cs2 ON cs2.charid = c.charid
	LEFT JOIN xidb.titles t ON t.titleid = cs.title
	LEFT JOIN xidb.accounts a ON a.id = c.accid
	LEFT JOIN forums.phpbb_users pu ON pu.user_email = a.registration_email 
	WHERE c.charid = {$charid}
	GROUP BY c.charid;
	SQL;
		$result = $db->sql_query($sql);
		$chardata = (array) $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$chardata['char_image'] = $this->races[$chardata['race']] . $this->faces[$chardata['face']] . '.jpg';
		$chardata['nationname'] = $this->nations[$chardata['nation']];
		$chardata['mainjob'] = $this->jobs[$chardata['mjob']];
		@$chardata['subjob'] = $this->jobs[$chardata['sjob']];
		$chardata['title'] = ucwords(strtolower(str_replace('_', ' ', $chardata['title'])));
		return $chardata;
	}

	public function xi_char_linkshells($charid)
	{
		global $db;
		$sql = <<<SQL
	
	SELECT *
	FROM xidb.linkshells l
	LEFT JOIN xidb.char_inventory ci ON ci.signature = l.name
	LEFT JOIN xidb.char_equip ce ON ce.slotid = ci.slot AND ce.charid = ci.charid 
	WHERE ce.equipslotid IN (16, 17)
	AND ci.charid = {$charid}
	ORDER BY ce.equipslotid;
	
	SQL;

		$result = $db->sql_query($sql);
		$linkshelldata = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $linkshelldata;
	}

	public function xi_char_equipment($charid)
	{
		global $db, $user, $races, $faces, $nations;

		$sql = <<<SQL
	SELECT i.*, ce.equipslotid, l.name linkshell_name, l.color linkshell_color, l.message linkshell_message
	FROM xidb.item_basic i
	LEFT JOIN xidb.char_inventory ci ON ci.itemId = i.itemid
	LEFT JOIN xidb.char_equip ce on ce.charid = ci.charid
	LEFT JOIN xidb.linkshells l on ci.signature = l.name
	WHERE ci.charid = {$charid}
	AND ci.location = ce.containerid
	AND ci.slot = ce.slotid
	ORDER BY equipslotid
	SQL;
		$result = $db->sql_query($sql);
		$equipdata = (array) $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$return = array();
		foreach ($equipdata as $item) {
			$return[$item['equipslotid']] = $item;
			$return[$item['equipslotid']]['name'] = ucwords(str_replace('_', ' ', $item['name']));
		}
		return $return;
	}
}
