<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

use madetoraid\lsbbb\includes\functions_xi;
use madetoraid\lsbbb\includes\functions_npc;

class npc_controller
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
	protected $npc;

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

		$this->npc = new functions_npc();
	}

	/**
	 * Controller handler for route /xi/npc/{npc_id}
	 *
	 * @param int $zone_id
	 * @param int $group_id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($npc_id)
	{
		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';
		$this->language->add_lang('common', 'madetoraid/lsbbb');
		$this->language->add_lang('zone', 'madetoraid/lsbbb');

		$this->template->assign_vars(array(
			'LSBBB_URL' 		=> $lsbbb_url,
			'LSBBB_ITEM_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_item'),
			'LSBBB_AH_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_ah'),
			'LSBBB_ZONE_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_zone'),
			'LSBBB_MOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_mob_group'),
		));

		// Get data for the page
		$npc_data = $this->npc->xi_npc_info($npc_id);

		if (sizeof($npc_data) > 0) {

			$zonename = strtoupper($npc_data['zone']);
			$this->template->assign_block_vars('npcrow', $npc_data);

			$this->template->assign_vars(array(
				'ZONE_NAME'		=> $this->language->lang($zonename),
			));
			
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
				'FORUM_NAME' => $this->language->lang($zonename),
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_zone_id', array('zone_id' => $npc_data['zoneid'])),
			));
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $npc_data['polutils_name'],
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_npc_id', array('npc_id' => $npc_id,)),
			));
		}
		else {
			redirect($this->helper->route('madetoraid_lsbbb_controller_default'));
		}
		page_header($this->language->lang('LSBBB_NPC') . ' - ' . $npc_data['name'] . ' (' . $this->language->lang($zonename) . ')');

		return $this->helper->render('@madetoraid_lsbbb/xi_npc_body.html', $npc_id);
	}
}
