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
	 * @param \phpbb\user\user			$user		User object
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language, \phpbb\request\request $request, \phpbb\user $user)
	{
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
		$this->request	= $request;
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
		$hide_unlisted  = $this->request->variable('h', 1);
		$ahreturn_id    = $this->request->variable('returnid', 0);
		$parent = '';
		$page_title = "Auction House";
		$ah_categories = $this->ah->xi_ah_categories();

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => 'Auction House',
			'U_VIEW_FORUM' => append_sid('/xi/ah/'),
		));
		if ($category_id == 0) {
			$ah_content = $this->ah->xi_ah_latest();
		} elseif ($category_id == 999) {
			if($ahreturn_id > 0) {
                $this->ah->xi_ah_returnitem($ahreturn_id);
        	}
			$ah_content = $this->ah->xi_ah_mine($this->user->data);
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => 'My Listings',
				'U_VIEW_FORUM' => append_sid('/xi/ah/999'),
			));
        	$page_title = "My Listings";
		}
		else {
			$ah_content = $this->ah->xi_ah_category($category_id, $hide_unlisted);
			if (sizeof($ah_content) > 0) {
				$this->template->assign_block_vars('navlinks', array(
					'FORUM_NAME' => $ah_content[0]['category'],
					'U_VIEW_FORUM' => append_sid('/xi/ah/' . $category_id),
				));
				$page_title = $ah_content[0]['category'];
			} else {
				$page_title = "Category Not Found";
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
