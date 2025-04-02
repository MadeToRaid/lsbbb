<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

/**
 * recipe functions
 */
class functions_recipe
{

	public function xi_get_recipe($item_id = 4381)
	{
		global $db;
		$return = array();
		if (is_numeric($item_id)) {
			$sql_array = array(
				'SELECT'	=> 'sr.crystal, sr.result, ibr.name result_name, sr.resulthq1, ibr1.name resulthq1_name, sr.resulthq2, ibr2.name resulthq2_name, sr.resulthq3, ibr3.name resulthq3_name,
					sr.resultqty, sr.resulthq1qty, sr.resulthq2qty, sr.resulthq3qty,
					sr.wood, sr.smith, sr.gold, sr.cloth, sr.leather, sr.bone, sr.alchemy, sr.cook, sr.ingredient1, sr.ingredient2, sr.ingredient3, sr.ingredient4, sr.ingredient5, sr.ingredient6, sr.ingredient7, sr.ingredient8,
					ib.name as crystal_name,
					ib1.name ingredient1_name,
					ib2.name ingredient2_name,
					ib3.name ingredient3_name,
					ib4.name ingredient4_name,
					ib5.name ingredient5_name,
					ib6.name ingredient6_name,
					ib7.name ingredient7_name,
					ib8.name ingredient8_name',
				'FROM'		=> array(
					'xidb.synth_recipes' => 'sr',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib'),
						'ON'	=> 'ib.itemid = sr.crystal',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib1'),
						'ON'	=> 'ib1.itemid = sr.Ingredient1',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib2'),
						'ON'	=> 'ib2.itemid = sr.Ingredient2',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib3'),
						'ON'	=> 'ib3.itemid = sr.Ingredient3',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib4'),
						'ON'	=> 'ib4.itemid = sr.Ingredient4',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib5'),
						'ON'	=> 'ib5.itemid = sr.Ingredient5',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib6'),
						'ON'	=> 'ib6.itemid = sr.Ingredient6',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib7'),
						'ON'	=> 'ib7.itemid = sr.Ingredient7',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib8'),
						'ON'	=> 'ib8.itemid = sr.Ingredient8',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ibr'),
						'ON'	=> 'sr.result = ibr.itemid',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ibr1'),
						'ON'	=> 'sr.resulthq1 = ibr1.itemid',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ibr2'),
						'ON'	=> 'sr.resulthq2 = ibr2.itemid',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ibr3'),
						'ON'	=> 'sr.resulthq3 = ibr3.itemid',
					),
				),
				'WHERE'		=> 'sr.result = ' . (int) $item_id . '
					OR sr.resulthq1 = ' . (int) $item_id . '
					OR sr.resulthq2 = ' . (int) $item_id . '
					OR sr.resulthq3 = ' . (int) $item_id,
				'ORDER_BY'	=> 'sr.wood, sr.smith, sr.gold, sr.cloth, sr.leather, sr.bone, sr.alchemy, sr.cook',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);

			$recipe_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			foreach ($recipe_data as $recipe) {
				$return[] = array(
					'result' => $recipe['result'],
					'result_name' => ucwords(str_replace('_', ' ', $recipe['result_name'])),
					'resulthq1' => $recipe['resulthq1'],
					'resulthq1_name' => ucwords(str_replace('_', ' ', $recipe['resulthq1_name'])),
					'resulthq2' => $recipe['resulthq2'],
					'resulthq2_name' => ucwords(str_replace('_', ' ', $recipe['resulthq2_name'])),
					'resulthq3' => $recipe['resulthq3'],
					'resulthq3_name' => ucwords(str_replace('_', ' ', $recipe['resulthq3_name'])),
					'resultqty' => $recipe['resultqty'],
					'resulthq1qty' => $recipe['resulthq1qty'],
					'resulthq2qty' => $recipe['resulthq2qty'],
					'resulthq3qty' => $recipe['resulthq3qty'],
					'wood' => $recipe['wood'],
					'smith' => $recipe['smith'],
					'gold' => $recipe['gold'],
					'cloth' => $recipe['cloth'],
					'leather' => $recipe['leather'],
					'bone' => $recipe['bone'],
					'alchemy' => $recipe['alchemy'],
					'cook' => $recipe['cook'],
					'crystal' => $recipe['crystal'],
					'crystal_name' => ucwords(str_replace('_', ' ', $recipe['crystal_name'])),
					'ingredient1' => $recipe['ingredient1'],
					'ingredient2' => $recipe['ingredient2'],
					'ingredient3' => $recipe['ingredient3'],
					'ingredient4' => $recipe['ingredient4'],
					'ingredient5' => $recipe['ingredient5'],
					'ingredient6' => $recipe['ingredient6'],
					'ingredient7' => $recipe['ingredient7'],
					'ingredient8' => $recipe['ingredient8'],
					'ingredient1_name' => ucwords(str_replace('_', ' ', $recipe['ingredient1_name'])),
					'ingredient2_name' => ucwords(str_replace('_', ' ', $recipe['ingredient2_name'])),
					'ingredient3_name' => ucwords(str_replace('_', ' ', $recipe['ingredient3_name'])),
					'ingredient4_name' => ucwords(str_replace('_', ' ', $recipe['ingredient4_name'])),
					'ingredient5_name' => ucwords(str_replace('_', ' ', $recipe['ingredient5_name'])),
					'ingredient6_name' => ucwords(str_replace('_', ' ', $recipe['ingredient6_name'])),
					'ingredient7_name' => ucwords(str_replace('_', ' ', $recipe['ingredient7_name'])),
					'ingredient8_name' => ucwords(str_replace('_', ' ', $recipe['ingredient8_name']))
				);
			}
		}
		return $return;
	}

	public function xi_get_ingredient($item_id = 4381)
	{
		global $db;
		$return = array();
		if (is_numeric($item_id)) {
			$sql_array = array(
				'SELECT'	=> 'sr.crystal, sr.result, ibr.name result_name, sr.resulthq1, ibr1.name resulthq1_name, sr.resulthq2, ibr2.name resulthq2_name, sr.resulthq3, ibr3.name resulthq3_name,
					sr.resultqty, sr.resulthq1qty, sr.resulthq2qty, sr.resulthq3qty,
					sr.wood, sr.smith, sr.gold, sr.cloth, sr.leather, sr.bone, sr.alchemy, sr.cook, sr.ingredient1, sr.ingredient2, sr.ingredient3, sr.ingredient4, sr.ingredient5, sr.ingredient6, sr.ingredient7, sr.ingredient8,
					ib.name as crystal_name,
					ib1.name ingredient1_name,
					ib2.name ingredient2_name,
					ib3.name ingredient3_name,
					ib4.name ingredient4_name,
					ib5.name ingredient5_name,
					ib6.name ingredient6_name,
					ib7.name ingredient7_name,
					ib8.name ingredient8_name',
				'FROM'		=> array(
					'xidb.synth_recipes' => 'sr',
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib'),
						'ON'	=> 'ib.itemid = sr.crystal',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib1'),
						'ON'	=> 'ib1.itemid = sr.Ingredient1',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib2'),
						'ON'	=> 'ib2.itemid = sr.Ingredient2',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib3'),
						'ON'	=> 'ib3.itemid = sr.Ingredient3',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib4'),
						'ON'	=> 'ib4.itemid = sr.Ingredient4',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib5'),
						'ON'	=> 'ib5.itemid = sr.Ingredient5',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib6'),
						'ON'	=> 'ib6.itemid = sr.Ingredient6',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib7'),
						'ON'	=> 'ib7.itemid = sr.Ingredient7',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ib8'),
						'ON'	=> 'ib8.itemid = sr.Ingredient8',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ibr'),
						'ON'	=> 'sr.result = ibr.itemid',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ibr1'),
						'ON'	=> 'sr.resulthq1 = ibr1.itemid',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ibr2'),
						'ON'	=> 'sr.resulthq2 = ibr2.itemid',
					),
					array(
						'FROM'	=> array('xidb.item_basic' => 'ibr3'),
						'ON'	=> 'sr.resulthq3 = ibr3.itemid',
					),
				),
				'WHERE'		=> 'ib1.itemid = ' . (int) $item_id . '
					OR ib2.itemid = ' . (int) $item_id . '
					OR ib3.itemid = ' . (int) $item_id . '
					OR ib4.itemid = ' . (int) $item_id . '
					OR ib5.itemid = ' . (int) $item_id . '
					OR ib6.itemid = ' . (int) $item_id . '
					OR ib7.itemid = ' . (int) $item_id . '
					OR ib8.itemid = ' . (int) $item_id,
				'ORDER_BY'	=> 'sr.wood, sr.smith, sr.gold, sr.cloth, sr.leather, sr.bone, sr.alchemy, sr.cook',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);

			$recipe_data = (array) $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			foreach ($recipe_data as $recipe) {
				$return[] = array(
					'result' => $recipe['result'],
					'result_name' => ucwords(str_replace('_', ' ', $recipe['result_name'])),
					'resulthq1' => $recipe['resulthq1'],
					'resulthq1_name' => ucwords(str_replace('_', ' ', $recipe['resulthq1_name'])),
					'resulthq2' => $recipe['resulthq2'],
					'resulthq2_name' => ucwords(str_replace('_', ' ', $recipe['resulthq2_name'])),
					'resulthq3' => $recipe['resulthq3'],
					'resulthq3_name' => ucwords(str_replace('_', ' ', $recipe['resulthq3_name'])),
					'resultqty' => $recipe['resultqty'],
					'resulthq1qty' => $recipe['resulthq1qty'],
					'resulthq2qty' => $recipe['resulthq2qty'],
					'resulthq3qty' => $recipe['resulthq3qty'],
					'wood' => $recipe['wood'],
					'smith' => $recipe['smith'],
					'gold' => $recipe['gold'],
					'cloth' => $recipe['cloth'],
					'leather' => $recipe['leather'],
					'bone' => $recipe['bone'],
					'alchemy' => $recipe['alchemy'],
					'cook' => $recipe['cook'],
					'crystal' => $recipe['crystal'],
					'crystal_name' => ucwords(str_replace('_', ' ', $recipe['crystal_name'])),
					'ingredient1' => $recipe['ingredient1'],
					'ingredient2' => $recipe['ingredient2'],
					'ingredient3' => $recipe['ingredient3'],
					'ingredient4' => $recipe['ingredient4'],
					'ingredient5' => $recipe['ingredient5'],
					'ingredient6' => $recipe['ingredient6'],
					'ingredient7' => $recipe['ingredient7'],
					'ingredient8' => $recipe['ingredient8'],
					'ingredient1_name' => ucwords(str_replace('_', ' ', $recipe['ingredient1_name'])),
					'ingredient2_name' => ucwords(str_replace('_', ' ', $recipe['ingredient2_name'])),
					'ingredient3_name' => ucwords(str_replace('_', ' ', $recipe['ingredient3_name'])),
					'ingredient4_name' => ucwords(str_replace('_', ' ', $recipe['ingredient4_name'])),
					'ingredient5_name' => ucwords(str_replace('_', ' ', $recipe['ingredient5_name'])),
					'ingredient6_name' => ucwords(str_replace('_', ' ', $recipe['ingredient6_name'])),
					'ingredient7_name' => ucwords(str_replace('_', ' ', $recipe['ingredient7_name'])),
					'ingredient8_name' => ucwords(str_replace('_', ' ', $recipe['ingredient8_name']))
				);
			}
		}
		return $return;
	}
}
