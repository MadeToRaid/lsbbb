<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

use madetoraid\lsbbb\includes\functions_xi;
use madetoraid\lsbbb\includes\functions_craft;

/**
 * PHPBB XI Server Integration main controller.
 */
class craft_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var \phpbb\request */
	protected $request;

	/** @var craft */
	protected $craft;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config		Config object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\language\language	$language	Language object
	 * @param \phpbb\pagination			$pagination	Pagination object
	 * @param \phpbb\request			$request	Request object
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language, \phpbb\pagination $pagination, \phpbb\request\request $request)
	{
		$this->config		= $config;
		$this->helper		= $helper;
		$this->template		= $template;
		$this->language		= $language;
		$this->pagination	= $pagination;
		$this->request		= $request;

		$this->craft = new functions_craft();
	}

	public function default()
	{
		return $this->helper->render('@madetoraid_lsbbb/xi_craft_body.html', 0);
	}

	/**
	 * Controller handler for route /xi/craft/{guild_id}
	 * 
	 * @param int $zone_id
	 * @param int $group_id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($guild_id, $craft_rank)
	{
		$this->language->add_lang('zone', 'madetoraid/lsbbb');
		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';
		$this->template->assign_vars(array(
			'LSBBB_URL' 		=> $lsbbb_url,
			'LSBBB_ITEM_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_item'),
			'LSBBB_AH_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_ah'),
			'LSBBB_ZONE_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_zone'),
			'LSBBB_MOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_mob_group'),
			'LSBBB_CRAFT_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_craft'),
			'LSBBB_THIS_CRAFT'  => $this->language->lang('LSBBB_' . strtoupper($this->craft->guildcols[$guild_id])),
			'LSBBB_THIS_CRAFT_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_craft_id', array('guild_id' => $guild_id)),
		));

		$craft_rank = $this->request->variable('rank', 0);

		// Get data for the page
		$recipe_data = $this->craft->xi_get_guild_recipes($guild_id, $craft_rank);
		if (sizeof($recipe_data) > 0) {

			// Assign data to template vars
			foreach ($recipe_data as $reciperow) {
				$this->template->assign_block_vars('reciperow', $reciperow);
			}

			$num_ranks = $this->craft->xi_get_num_ranks($guild_id);
			$num_recipes = $this->craft->xi_get_num_recipes($guild_id);
			$this->pagination->generate_template_pagination($this->helper->route('madetoraid_lsbbb_controller_craft_id', array('guild_id' => $guild_id)), 'pagination', 'rank', $num_ranks, 1, $craft_rank);

			$this->template->assign_vars([
				'TOTAL_POSTS'		=> $num_recipes,
				'PAGE_NUMER'        => $craft_rank,
			]);

			// Set up navlink
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $this->language->lang('LSBBB_PAGE'),
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_default'),
			));
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $this->language->lang('LSBBB_CRAFT'),
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_craft'),
			));
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME' => $this->language->lang('LSBBB_' . strtoupper($this->craft->guildcols[$guild_id])),
				'U_VIEW_FORUM' => $this->helper->route('madetoraid_lsbbb_controller_craft_id', array('guild_id' => $guild_id)),
			));
		} else {
			redirect($this->helper->route('madetoraid_lsbbb_controller_craft'));
		}
		page_header($this->language->lang('LSBBB_CRAFT') . ' - ' . $this->language->lang('LSBBB_' . strtoupper($this->craft->guildcols[$guild_id])) . ' (' . $craft_rank + 1 . '/' . $num_ranks . ')' );

		return $this->helper->render('@madetoraid_lsbbb/xi_craft_body.html', $guild_id);
	}
}
