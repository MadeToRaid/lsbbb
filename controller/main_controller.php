<?php
/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

use madetoraid\lsbbb\includes\functions_census;

/**
 * PHPBB XI Server Integration main controller.
 */
class main_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var madetoraid\lsbbb\includes\functions_census */
	protected $census;

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

		$this->census 	= new functions_census();
	}

	/**
	 * Controller handler for route /demo/{name}
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($name = "default")
	{
		// Set up navlink
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => "FFXI",
			'U_VIEW_FORUM' => append_sid('/xi/'),
		));

		// Set some default page header values
		$page_title = "Final Fantasy XI Private Server";
		$page_extras = true;

		// Get data for the page
		$census_data = $this->census->xi_census_naton();
		$chistory_data = $this->census->xi_census_chistory();

		// Assign data to template vars
		$this->template->assign_block_vars('censusdata', $census_data );
		$this->template->assign_vars($chistory_data);

		// Display the page
		page_header($page_title);

		return $this->helper->render('@madetoraid_lsbbb/xi_index_body.html', $name);
	}
}
