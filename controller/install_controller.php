<?php
/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

/**
 *  Install client controller.
 */
class install_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

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
	}

	/**
	 * Controller handler for route /install/
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($page = 'index')
	{
		$page_title = $this->language->lang('LSBBB_NAV_INSTALL');
		
		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';
		$this->template->assign_vars(array(
			'LSBBB_URL' 		=> $lsbbb_url,
			'LSBBB_ITEM_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_item'),
			'LSBBB_AH_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_ah'),
			'LSBBB_ZONE_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_zone'),
			'LSBBB_MOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_mob_group'),
			'LSBBB_LINUX_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_install_page', array('page' => 'linux')),
			'LSBBB_WINDOWS_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_install_page', array('page' => 'windows')),
		));

		// Set up navlink
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_PAGE'),
			'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_default'),
		));
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_NAV_INSTALL'),
			'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_install'),
		));
		switch($page) {
			case "windows":
				$page_title .= ' - ' . $this->language->lang('LSBBB_WINDOWS');
				page_header($page_title, true);
				$this->template->assign_block_vars('navlinks', array(
					'FORUM_NAME' => $this->language->lang('LSBBB_WINDOWS'),
					'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_install'),
				));
				return $this->helper->render('@madetoraid_lsbbb/xi_install_windows.html', 0);
			case "linux":
				$page_title .= ' - ' . $this->language->lang('LSBBB_LINUX');
				page_header($page_title, true);
				$this->template->assign_block_vars('navlinks', array(
					'FORUM_NAME' => $this->language->lang('LSBBB_LINUX'),
					'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_install'),
				));
				return $this->helper->render('@madetoraid_lsbbb/xi_install_linux.html', 0);
			default:
				page_header($page_title, true);
				return $this->helper->render('@madetoraid_lsbbb/xi_install_index.html', 0);
		}
	}
}