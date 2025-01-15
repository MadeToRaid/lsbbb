<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

/**
 * census functions
 */
class functions_census
{
	public function xi_census_naton()
	{
		global $db;
		$sql = <<<SQL
	
	SELECT
	(SELECT COUNT(c.nation) FROM xidb.chars c WHERE c.nation = 0) sandoria_population,
	(SELECT COUNT(c.nation) FROM xidb.chars c WHERE c.nation = 1) bastok_population,
	(SELECT COUNT(c.nation) FROM xidb.chars c WHERE c.nation = 2) windurst_population,
	(SELECT SUM(ci.quantity) FROM xidb.char_inventory ci WHERE itemid = 65535) total_gil,
	(SELECT FLOOR(COUNT(cl.race)*100/t.s) FROM xidb.char_look cl CROSS JOIN (SELECT COUNT(race) s FROM xidb.char_look) t WHERE cl.race IN (1,2)) hume_percentage,
	(SELECT FLOOR(COUNT(cl.race)*100/t.s) FROM xidb.char_look cl CROSS JOIN (SELECT COUNT(race) s FROM xidb.char_look) t WHERE cl.race IN (3,4)) elvaan_percentage,
	(SELECT FLOOR(COUNT(cl.race)*100/t.s) FROM xidb.char_look cl CROSS JOIN (SELECT COUNT(race) s FROM xidb.char_look) t WHERE cl.race IN (5,6)) tarutaru_percentage,
	(SELECT FLOOR(COUNT(cl.race)*100/t.s) FROM xidb.char_look cl CROSS JOIN (SELECT COUNT(race) s FROM xidb.char_look) t WHERE cl.race IN (7)) mithra_percentage,
	(SELECT FLOOR(COUNT(cl.race)*100/t.s) FROM xidb.char_look cl CROSS JOIN (SELECT COUNT(race) s FROM xidb.char_look) t WHERE cl.race IN (8)) galka_percentage,
	(SELECT SUM(war) FROM xidb.char_jobs) war_total,
	(SELECT SUM(mnk) FROM xidb.char_jobs) mnk_total,
	(SELECT SUM(whm) FROM xidb.char_jobs) whm_total,
	(SELECT SUM(blm) FROM xidb.char_jobs) blm_total,
	(SELECT SUM(rdm) FROM xidb.char_jobs) rdm_total,
	(SELECT SUM(thf) FROM xidb.char_jobs) thf_total,
	(SELECT SUM(pld) FROM xidb.char_jobs) pld_total,
	(SELECT SUM(drk) FROM xidb.char_jobs) drk_total,
	(SELECT SUM(bst) FROM xidb.char_jobs) bst_total,
	(SELECT SUM(brd) FROM xidb.char_jobs) brd_total,
	(SELECT SUM(rng) FROM xidb.char_jobs) rng_total,
	(SELECT SUM(sam) FROM xidb.char_jobs) sam_total,
	(SELECT SUM(nin) FROM xidb.char_jobs) nin_total,
	(SELECT SUM(drg) FROM xidb.char_jobs) drg_total,
	(SELECT SUM(smn) FROM xidb.char_jobs) smn_total,
	(SELECT SUM(blu) FROM xidb.char_jobs) blu_total,
	(SELECT SUM(cor) FROM xidb.char_jobs) cor_total,
	(SELECT SUM(pup) FROM xidb.char_jobs) pup_total,
	(SELECT SUM(sch) FROM xidb.char_jobs) sch_total,
	(SELECT SUM(dnc) FROM xidb.char_jobs) dnc_total,
	(SELECT ROUND(SUM(cj.war)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) war_percentage,
	(SELECT ROUND(SUM(cj.mnk)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) mnk_percentage,
	(SELECT ROUND(SUM(cj.whm)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) whm_percentage,
	(SELECT ROUND(SUM(cj.blm)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) blm_percentage,
	(SELECT ROUND(SUM(cj.rdm)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) rdm_percentage,
	(SELECT ROUND(SUM(cj.thf)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) thf_percentage,
	(SELECT ROUND(SUM(cj.pld)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) pld_percentage,
	(SELECT ROUND(SUM(cj.drk)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) drk_percentage,
	(SELECT ROUND(SUM(cj.bst)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) bst_percentage,
	(SELECT ROUND(SUM(cj.brd)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) brd_percentage,
	(SELECT ROUND(SUM(cj.rng)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) rng_percentage,
	(SELECT ROUND(SUM(cj.sam)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) sam_percentage,
	(SELECT ROUND(SUM(cj.nin)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) nin_percentage,
	(SELECT ROUND(SUM(cj.drg)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) drg_percentage,
	(SELECT ROUND(SUM(cj.smn)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) smn_percentage,
	(SELECT ROUND(SUM(cj.blu)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) blu_percentage,
	(SELECT ROUND(SUM(cj.cor)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) cor_percentage,
	(SELECT ROUND(SUM(cj.pup)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) pup_percentage,
	(SELECT ROUND(SUM(cj.sch)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) sch_percentage,
	(SELECT ROUND(SUM(cj.dnc)*100/t.s) FROM xidb.char_jobs cj CROSS JOIN (SELECT (SUM(war+mnk+whm+blm+rdm+thf+pld+drk+bst+brd+rng+sam+nin+drg+smn+blu+cor+pup+sch+dnc)) s FROM xidb.char_jobs) t) dnc_percentage,
	(SELECT COUNT(ah.id) FROM xidb.auction_house ah) ah_count
	;
	
	SQL;
		$result = $db->sql_query($sql);
		$censusdata = (array) $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $censusdata;
	}

	public function xi_census_chistory()
	{
		global $db;
		$sql_array = array(
			'SELECT'	=> 'SUM(ch.enemies_defeated) enemies_defeated, SUM(ch.times_knocked_out) times_knocked_out, SUM(ch.distance_travelled) distance_travelled, SUM(ch.battles_fought) battles_fought, SUM(ch.spells_cast) spells_cast',
			'FROM'		=> array(
				'xidb.char_history' => 'ch',
			),
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$chistory_data = (array) $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		return $chistory_data;
	}
}
