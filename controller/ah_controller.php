<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

use madetoraid\lsbbb\includes\functions;
use madetoraid\lsbbb\includes\functions_xi;
use madetoraid\lsbbb\includes\functions_ah;

/**
 * PHPBB XI Server Integration AH controller.
 */
class ah_controller
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

	/** @var root_path */
	protected $root_path;

	/** @var \phpbb\user\user */
	protected $user;

	/** @var madetoraid\lsbbb\includes\functions_xi */
	protected $xi;

	/** @var madetoraid\lsbbb\includes\functions_ah */
	protected $ah;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config		Config object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\language\language	$language	Language object
	 * @param \phpbb\request\request	$request	Request object
	 * @param string $root_path
	 * @param \phpbb\user\user			$user		User object
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language, \phpbb\request\request $request, $root_path, \phpbb\user $user)
	{
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
		$this->request	= $request;
		$this->root_path = $root_path;
		$this->user	= $user;

		$this->xi	= new functions_xi();
		$this->ah	= new functions_ah();
	}

	/**
	 * Controller handler for route /xi/ah/{category_id}
	 *
	 * @param int $category_id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle(int $category_id = 0)
	{
		$this->language->add_lang('common', 'madetoraid/lsbbb');

		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';
		$this->template->assign_vars(array(
			'LSBBB_URL' 		=> $lsbbb_url,
			'LSBBB_ITEM_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_item'),
			'LSBBB_AH_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_ah'),
			'LSBBB_ZONE_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_zone'),
			'LSBBB_MOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_mob_group'),
		));

		$hide_unlisted  = $this->request->variable('h', 1);
		$ahreturn_id    = $this->request->variable('returnid', 0);
		$parent 		= '';
		$page_title 	= $this->language->lang('LSBBB_AH');
		$ah_categories 	= $this->ah->xi_ah_categories();

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_AH'),
			'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_ah')),
		));
		$this->template->assign_vars(array(
			'LSBBB_IMAGES_URL' => generate_board_url() . '/ext/madetoraid/lsbbb/'
		));
		if ($category_id == 0) {
			$ah_content = $this->ah->xi_ah_latest();
		} elseif ($category_id == 999) {
			if ($ahreturn_id > 0) {
				$this->ah->xi_ah_returnitem($ahreturn_id);
			}
			$ah_content = $this->ah->xi_ah_mine($this->user->data);
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $this->language->lang('LSBBB_MY_LISTINGS'),
				'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_ah') . '999'),
			));
			$page_title = $this->language->lang('LSBBB_MY_LISTINGS');
		} else {
			$ah_content = $this->ah->xi_ah_category($category_id, $hide_unlisted);
			if (sizeof($ah_content) > 0) {
				$this->template->assign_block_vars('navlinks', array(
					'FORUM_NAME' => $ah_content[0]['category'],
					'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_ah') . $category_id),
				));
				$page_title = $ah_content[0]['category'];
			} else {
				$page_title = $this->language->lang('LSBBB_CATEGORYNOTFOUND');
				redirect($this->helper->route('madetoraid_lsbbb_controller_ah'));
			}
			$this->template->assign_vars(array('is_category' => true, 'hide_unlisted' => $hide_unlisted));
		}
		foreach ($ah_categories as $cat_row) {
			if ($cat_row['parent'] != $parent) {
				$parent = $cat_row['parent'];
				$parent_row = array('id' => 0, 'name' => $parent);
				$this->template->assign_block_vars('ahcatrow', $parent_row);
			}
			$this->template->assign_block_vars('ahcatrow', $cat_row);
		}
		foreach ($ah_content as $ah_row) {
			$ah_row['name'] = $this->xi->xi_ucwords($ah_row['name']);
			$ah_row['sortname'] = $this->xi->xi_ucwords($ah_row['sortname']);
			if (isset($ah_row['avgprice'])) {
				$ah_row['avgprice'] = number_format($ah_row['avgprice']);
			}
			$this->template->assign_block_vars('ahrow', $ah_row);
		}
		$this->template->assign_vars(array('catid' => $category_id));
		page_header($page_title);
		return $this->helper->render('@madetoraid_lsbbb/xi_ah_list.html', $category_id);
	}
}
