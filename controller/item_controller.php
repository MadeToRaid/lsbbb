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
use madetoraid\lsbbb\includes\functions_item;
use madetoraid\lsbbb\includes\functions_ah;
use madetoraid\lsbbb\includes\functions_recipe;
use madetoraid\lsbbb\includes\functions_fish;

/**
 * PHPBB XI Server Integration AH controller.
 */
class item_controller
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

	/** @var madetoraid\lsbbb\includes\functions_xi */
	protected $xi;

	/** @var madetoraid\lsbbb\includes\functions_item */
	protected $item;

	/** @var madetoraid\lsbbb\includes\functions_ah */
	protected $ah;

	/** @var madetoraid\lsbbb\includes\functions_recipe */
	protected $recipe;

	/** @var madetoraid\lsbbb\includes\functions_fish */
	protected $fish;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config		Config object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\language\language	$language	Language object
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language, \phpbb\request\request $request)
	{
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
		$this->request	= $request;

		$this->xi		= new functions_xi();
		$this->item		= new functions_item();
		$this->ah		= new functions_ah();
		$this->recipe	= new functions_recipe();
		$this->fish		= new functions_fish();

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => "FFXI",
			'U_VIEW_FORUM' => append_sid('/xi/'),
		));
	}

	/**
	 * Controller handler for route /xi/ah/{item_id}
	 *
	 * @param int $item_id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle(int $item_id = 0)
	{

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => 'Items',
			'U_VIEW_FORUM' => append_sid('/xi/item/'),
		));

		$page_title = "Items - ";
		$page_extras = true;
		$search_term = $this->request->variable('s', 'Kraken');

		//$item_id = $request->variable('i', '0');
		//$search_term = $request->variable('s', 'Kraken');

		if (is_numeric($item_id) && $item_id > 0) {
			$item_data 	= $this->item->xi_get_item($item_id);
			$recipe_data 	= $this->recipe->xi_get_recipe($item_id);
			$drop_data 	= $this->item->xi_drop_search($item_id);
			$fish_data 	= $this->fish->xi_fish_info($item_id);
			$ah_data 	= $this->ah->xi_auction_history($item_id, 10);
			$page_title 	.= $item_data[0]['name'];

			if (sizeof($item_data) > 0) {
				foreach ($item_data as $item_row) {
					$this->template->assign_block_vars('itemrow', $item_row);
				}
				foreach ($recipe_data as $recipe_row) {
					$this->template->assign_block_vars('reciperow', $recipe_row);
				}
				foreach ($drop_data as $drop_row) {
					$this->template->assign_block_vars('droprow', $drop_row);
				}
				foreach ($fish_data as $fish_row) {
					$this->template->assign_block_vars('fishrow', $fish_row);
				}
				foreach ($ah_data as $ah_row) {
					$this->template->assign_block_vars('ahrow', $ah_row);
				}
				if ($item_data[0]['category']) {
					$this->template->assign_block_vars('navlinks', array(
						'FORUM_NAME' => $item_data[0]['category'],
						'U_VIEW_FORUM' => append_sid('/xi/ah/' . $item_data[0]['category_id']),
					));
				}
				$this->template->assign_block_vars('navlinks', array(
					'FORUM_NAME' => $item_data[0]['name'],
					'U_VIEW_FORUM' => append_sid('/xi/item/' . $item_id),
				));
			}
		} else {
			$page_title .= $this->xi->xi_ucwords($search_term);

			$item_data = $this->item->xi_item_search($search_term);

			if (sizeof($item_data) > 0) {
				$this->template->assign_block_vars('navlinks', array(
					'FORUM_NAME' => ucwords($search_term),
					'U_VIEW_FORUM' => append_sid('/xi/item/'),
				));
				foreach ($item_data as $item_row) {
					$this->template->assign_block_vars('itemrow', $item_row);
				}
			} else {
				$page_extras = false;
				$page_title .= "Not Found";
			}
		}

		page_header($page_title);
		return $this->helper->render('@madetoraid_lsbbb/xi_item_body.html', $item_id);
	}
}
