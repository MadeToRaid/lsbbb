<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

use madetoraid\lsbbb\includes\functions_xi;
use madetoraid\lsbbb\includes\functions_mob;

/**
 * PHPBB XI Server Integration main controller.
 */
class mob_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var mob */
	protected $mob;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config		Config object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\language\language	$language	Language object
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language)
	{
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;

		$this->mob = new functions_mob();
	}

	/**
	 * Controller handler for route /xi/zone/{zone_id}/mobgroup/{group_id}
	 *
	 * @param int $zone_id
	 * @param int $group_id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($zone_id, $group_id)
	{
		$this->language->add_lang('zone', 'madetoraid/lsbbb');
		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';
		$lsbbb_youtube_key = $this->config['madetoraid_lsbbb_youtube_key'];
		$this->template->assign_vars(array(
			'LSBBB_URL' 		=> $lsbbb_url,
			'LSBBB_ITEM_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_item'),
			'LSBBB_AH_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_ah'),
			'LSBBB_ZONE_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_zone'),
			'LSBBB_MOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_mob_group'),
			'LSBBB_ISGROUP'		=> true,
		));

		// Get data for the page
		$mob_data = $this->mob->xi_mob_group_info($zone_id, $group_id);
		if (sizeof($mob_data) > 0) {
			$drop_data = $this->mob->xi_mob_drops($zone_id, $group_id);
			$mob_spawns = $this->mob->xi_mob_group_spawns($zone_id, $group_id);

			if ($lsbbb_youtube_key != '') {
				$this->template->assign_vars(array('LSBBB_YOUTUBE_KEY' => $lsbbb_youtube_key));
			}

			// Assign data to template vars
			foreach ($mob_data as $mobrow) {
				$mobrow['moburl'] = $this->helper->route('madetoraid_lsbbb_controller_mob_group', array('zone_id' => $zone_id, 'group_id' => $group_id));
				if ($mobrow['mobtype'] == 2 && $this->config['madetoraid_lsbbb_youtube_key'] != '' && $this->config['madetoraid_lsbbb_youtube_nm'] == 1) {
					$this->template->assign_vars(array('SHOWVIDEOS' => true));
				} else {
					$this->template->assign_vars(array('SHOWVIDEOS' => false));
				}

				$this->template->assign_block_vars('mobrow', $mobrow);
			}
			foreach ($drop_data as $droprow) {
				$this->template->assign_block_vars('droprow', $droprow);
			}
			foreach ($mob_spawns as $spawnrow) {
				$zone_name = str_replace('-', '_', strtoupper($spawnrow['zone']));
				$zone_name = preg_replace('/[^A-Za-z0-9_]/', '', $zone_name);
				$spawnrow['zonename'] = $this->language->lang($zone_name);
				$this->template->assign_block_vars('spawnrow', $spawnrow);
			}

			// Set up navlink
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $this->language->lang('LSBBB_PAGE'),
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_default'),
			));
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $this->language->lang('LSBBB_VANADIEL'),
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_zone'),
			));
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $mob_data[0]['zone'],
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_zone') . $zone_id,
			));
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $mob_data[0]['name'],
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_mob_group', array('zone_id' => $zone_id, 'group_id' => $group_id)),
			));
		} else {
			redirect($this->helper->route('madetoraid_lsbbb_controller_zone_id', array('zone_id' => $zone_id)));
		}
		page_header($this->language->lang('LSBBB_MOBGROUP') . ' - ' . $mob_data[0]['name'] . ' (' . $mob_data[0]['zone'] . ')');

		return $this->helper->render('@madetoraid_lsbbb/xi_mob_body.html', $group_id);
	}

	public function handle_mob($mob_id)
	{
		$this->language->add_lang('zone', 'madetoraid/lsbbb');
		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';

		$mob_info = $this->mob->xi_mob_info($mob_id);
		$this->template->assign_vars(array(
			'LSBBB_URL'				=> $lsbbb_url,
			'LSBBB_ITEM_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_item'),
			'LSBBB_AH_URL'			=> $this->helper->route('madetoraid_lsbbb_controller_ah'),
			'LSBBB_ZONE_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_zone'),
			'LSBBB_MOB_URL'			=> $this->helper->route('madetoraid_lsbbb_controller_mob_group'),
			'LSBBB_MOB_GROUP_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_mob_group', array('zone_id' => $mob_info['zoneid'], 'group_id' => $mob_info['groupid'])),
		));

		// Set up navlink
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_PAGE'),
			'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_default'),
		));
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $mob_info['name'],
			'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_mob_id', array('mob_id' => $mob_id)),
		));

		$zone_name = str_replace('-', '_', strtoupper($mob_info['zone']));
		$zone_name = preg_replace('/[^A-Za-z0-9_]/', '', $zone_name);
		$mob_info['zone'] = $this->language->lang($zone_name);
		$this->template->assign_block_vars('mobrow', $mob_info);
		page_header($this->language->lang('LSBBB_MOBGROUP') . ' - ' . $mob_info['name'] . ' (' . $mob_id . ')');

		return $this->helper->render('@madetoraid_lsbbb/xi_mob_body.html', $mob_id);
	}
}
