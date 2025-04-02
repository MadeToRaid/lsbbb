<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

use madetoraid\lsbbb\includes\functions_xi;
use madetoraid\lsbbb\includes\functions_job;
use phpbb\path_helper;

/**
 * PHPBB XI Server Integration AH controller.
 */
class job_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var madetoraid\lsbbb\includes\functions_xi */
	protected $xi;

	/** @var madetoraid\lsbbb\includes\functions_job */
	protected $job;

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
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language)
	{
		$this->config		= $config;
		$this->helper		= $helper;
		$this->template		= $template;
		$this->language		= $language;

		$this->xi	= new functions_xi();
		$this->job	= new functions_job();

	}

	/**
	 * Controller handler for route /xi/job/
	 *
	 * @param int $job_id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function default()
	{
		$this->language->add_lang('common', 'madetoraid/lsbbb');
		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';
		$this->template->assign_vars(array(	
			'LSBBB_URL' 		=> $lsbbb_url,
			'LSBBB_JOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_job'),
			'SHOW_JOB_LIST'		=> 1,
		));
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_PAGE'),
			'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_default'),
		));
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_JOBS'),
			'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_job')),
		));
		return $this->helper->render('@madetoraid_lsbbb/xi_job_body.html', $this->language->lang('LSBBB_JOBS'));
	}

	/**
	 * Controller handler for route /xi/job/{jobid}
	 *
	 * @param int $job_id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle(int $job_id = 0)
	{
		$this->language->add_lang('common', 'madetoraid/lsbbb');
		$this->language->add_lang('zone', 'madetoraid/lsbbb');

		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';
		$this->template->assign_vars(array(
			'LSBBB_URL' 		=> $lsbbb_url,
			'LSBBB_ITEM_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_item'),
			'LSBBB_AH_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_ah'),
			'LSBBB_ZONE_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_zone'),
			'LSBBB_MOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_mob_group'),
		));

		$page_title = $this->language->lang('LSBBB_JOBS');

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_PAGE'),
			'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_default'),
		));
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_JOBS'),
			'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_job')),
		));
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_' . $this->job->jobs[$job_id] . '_LONG'),
			'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_job_id', array('job_id' => $job_id))),
		));

		if ($job_id > 0) {
			$ability_data = $this->job->xi_job_abilities($job_id);
			$spell_data = $this->job->xi_job_spells($job_id);
			$trait_data = $this->job->xi_job_traits($job_id);
			
			$page_title = $this->language->lang('LSBBB_JOBS') . " - " . $this->language->lang('LSBBB_' . $this->job->jobs[$job_id] . '_LONG') . ' ('.$this->job->jobs[$job_id].')';
			foreach ($trait_data as $trait_row) {
				$this->template->assign_block_vars('traitrow', $trait_row);
			}
			foreach ($ability_data as $ability_row) {
				$this->template->assign_block_vars('abilityrow', $ability_row);
			}
			foreach ($spell_data as $spell_row) {
				$spell_row['item_url'] = $this->helper->route('madetoraid_lsbbb_controller_item_id', array('item_id' => $spell_row['itemid']));
				$this->template->assign_block_vars('spellrow', $spell_row);
			}

		} else {
			
		}
		page_header($page_title);
		return $this->helper->render('@madetoraid_lsbbb/xi_job_body.html', $job_id);
	}
}
