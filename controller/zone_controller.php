<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

use madetoraid\lsbbb\includes\functions_xi;
use madetoraid\lsbbb\includes\functions_zone;
use madetoraid\lsbbb\includes\functions_fish;
use phpbb\path_helper;

/**
 * PHPBB XI Server Integration AH controller.
 */
class zone_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user\user */
	protected $user;

	/** @var madetoraid\lsbbb\includes\functions_xi */
	protected $xi;

	/** @var madetoraid\lsbbb\includes\functions_zone */
	protected $zone;

	/** @var madetoraid\lsbbb\includes\functions_fish */
	protected $fish;

	/** @var path_helper */
	protected $root_path;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config			Config object
	 * @param \phpbb\controller\helper			$helper			Controller helper object
	 * @param \phpbb\template\template			$template		Template object
	 * @param \phpbb\language\language			$language		Language object
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language, $root_path)
	{
		$this->config		= $config;
		$this->helper		= $helper;
		$this->template		= $template;
		$this->language		= $language;

		$this->xi	= new functions_xi();
		$this->zone	= new functions_zone();
		$this->fish	= new functions_fish();

		$this->root_path = $root_path;
	}

	/**
	 * Controller handler for route /xi/ah/{itemid}
	 *
	 * @param int $itemid
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle(int $zone_id = 0)
	{
		$page_title = "Vana'diel World Map";

		$l_message = !$this->config['madetoraid_lsbbb_goodbye'] ? 'LSBBB_HELLO' : 'LSBBB_GOODBYE';
		$this->template->assign_var('LSBBB_MESSAGE', $this->language->lang($l_message, $zone_id));
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => "Vana'diel",
			'U_VIEW_FORUM' => append_sid('/xi/zone/'),
		));

		if ($zone_id > 0) {
			$zone_data = $this->zone->xi_zone_info($zone_id);
			$page_title = "Zones - " . $zone_data[0]['zone_name'];
			foreach ($zone_data as $zone_row) {
				$this->template->assign_block_vars('zonerow', $zone_row);
			}

			$zone_bcnm_data = $this->zone->xi_zone_bcnm_info($zone_id);
			foreach ($zone_bcnm_data as $bcnm_row) {
				$this->template->assign_block_vars('bcnmrow', $bcnm_row);
			}

			$zone_npc_data = $this->zone->xi_zone_npcs($zone_id);
			foreach ($zone_npc_data as $npc_row) {
				$this->template->assign_block_vars('npcrow', $npc_row);
			}

			$zone_mob_data = $this->zone->xi_zone_mobs($zone_id);
			foreach ($zone_mob_data as $mob_row) {
				$this->template->assign_block_vars('mobrow', $mob_row);
			}

			$zone_link_data = $this->zone->xi_zone_links($zone_id);
			foreach ($zone_link_data as $link_row) {
				$this->template->assign_block_vars('linkrow', $link_row);
			}

			$zone_maps = $this->zone->xi_zone_maps($zone_data[0]['zone_name'], $this->root_path);
			foreach ($zone_maps as $map_row) {
				$this->template->assign_block_vars('maprow', $map_row);
			}

			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $zone_data[0]['zone_name'],
				'U_VIEW_FORUM' => append_sid('/xi/zone/' . $zone_id),
			));
		} else {
			$conquest_data = $this->zone->xi_conquest_info();
			foreach ($conquest_data as $conquest_row) {
				$this->template->assign_var($conquest_row['region_name'], $conquest_row['region_control']);
			}
			$all_zones_data = $this->zone->xi_get_all_zones();
			foreach ($all_zones_data as $all_zones_row) {
				$this->template->assign_block_vars('allzonesrow', $all_zones_row);
			}
		}
		page_header($page_title);
		return $this->helper->render('@madetoraid_lsbbb/xi_zone_body.html', $zone_id);
	}
}
