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



		$page_title = "Final Fantasy XI Private Server";
		$page_extras = true;

		// Get data for the page
		$mob_data = $this->mob->xi_mob_info($zone_id, $group_id);
		$drop_data = $this->mob->xi_mob_drops($zone_id, $group_id);

		// Assign data to template vars
		foreach($mob_data as $mobrow) {
			$this->template->assign_block_vars('mobrow', $mobrow );
		}
		foreach($drop_data as $droprow) {
			$this->template->assign_block_vars('droprow', $droprow );
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
		page_header($this->language->lang('LSBBB_MOBGROUP') . ' - ' . $mob_data[0]['name'] . ' (' . $mob_data[0]['zone'] . ')');

		return $this->helper->render('@madetoraid_lsbbb/xi_mob_body.html', $group_id);
	}
}
