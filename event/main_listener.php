<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\event;

/**
 * @ignore
 */

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * PHPBB XI Server Integration Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'								=> 'load_language_on_setup',
			'core.viewonline_overwrite_location'			=> 'viewonline_page',
			'core.viewonline_modify_sql'					=> 'xi_view_online_modify_sql',
			//'core.display_forums_modify_template_vars'	=> 'display_forums_modify_template_vars',
			'core.page_header'								=> 'xi_add_page_header_link',
			'core.ucp_register_user_row_after'				=> 'xi_create_account',
			'core.ucp_activate_after'						=> 'xi_activate_account',
			'core.ucp_profile_reg_details_validate'			=> 'xi_update_password',
			'core.obtain_users_online_string_sql'			=> 'xi_obtain_users_online_string_sql',
			//'core.user_format_date_override'				=> 'xi_vanadiel_date'
			//'core.user_active_flip_after'					=> 'xi_flip_account',
			//'core.mcp_ban_main'							=> 'xi_ban_account',
			//'core.make_jumpbox_modify_forum_list'			=> 'xi_jumpbox',
		];
	}

	/* @var \phpbb\language\language */
	protected $language;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/** @var string phpEx */
	protected $php_ext;

	/** @var symfony_request */
	protected $symfony_request;

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language	$language	Language object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param object					$symfony_request	Symfony object
	 * @param string                    $php_ext    phpEx
	 */
	public function __construct(\phpbb\language\language $language, \phpbb\controller\helper $helper, \phpbb\template\template $template, $symfony_request, $php_ext)
	{
		$this->language 		= $language;
		$this->helper   		= $helper;
		$this->template 		= $template;
		$this->php_ext  		= $php_ext;
		$this->symfony_request 	= $symfony_request;
	}

	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'madetoraid/lsbbb',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Update the users online to include players logged into the XI server
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function xi_obtain_users_online_string_sql($event)
	{
		global $db;
		$online_users = $event['online_users'];

		$xi_sql_ary  = array(
			'SELECT'	=> 'pu.user_id session_user_id, INET_NTOA(client_addr) session_ip, 1 session_viewonline',
			'FROM'		=> array(
				'xidb.accounts_sessions' => 'as2',
				'xidb.accounts' => 'a',
				'forums.phpbb_users' => 'pu'
			),
			'WHERE'		=> 'a.id = as2.accid
				AND pu.user_email = a.registration_email',
		);
		$result = $db->sql_query($db->sql_build_query('SELECT', $xi_sql_ary));
		while ($row = $db->sql_fetchrow($result)) {
			if (!isset($online_users['online_users'][$row['session_user_id']])) {
				$online_users['online_users'][$row['session_user_id']] = (int) $row['session_user_id'];
				if ($row['session_viewonline']) {
					$online_users['visible_online']++;
				} else {
					$online_users['hidden_users'][$row['session_user_id']] = (int) $row['session_user_id'];
					$online_users['hidden_online']++;
				}
			}
		}
		$sql_ary = array(
			'SELECT'        => 'u.username, u.username_clean, u.user_id, u.user_type, u.user_allow_viewonline, u.user_colour',
			'FROM'          => array(
				USERS_TABLE     => 'u',
			),
			'WHERE'         => $db->sql_in_set('u.user_id', $online_users['online_users']),
			'ORDER_BY'      => 'u.username_clean ASC',
		);
		$event['online_users'] = $online_users;
		$event['sql_ary'] = $sql_ary;
	}

	/**
	 * Update the users online to include players logged into the XI server
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function xi_view_online_modify_sql($event)
	{
		$sql_ary_from = array(
				USERS_TABLE             => 'u',
				"(SELECT * FROM forums.phpbb_sessions ps
					UNION
					SELECT CONCAT(as2.client_addr, as2.client_port) session_id, pu.user_id session_user_id, UNIX_TIMESTAMP(a.timelastmodify) session_last_visit, UNIX_TIMESTAMP(a.timelastmodify) session_start, UNIX_TIMESTAMP() session_time, INET_NTOA(as2.client_addr) session_ip, \"FINAL FANTASY XI\" session_browser, z.name session_forwarded_for, CONCAT('app.php/xi/zone/', c.pos_zone) session_page, pu.user_allow_viewonline session_viewonline, 0 session_autologin, 0 session_admin, 0 session_forum_id
					FROM xidb.accounts_sessions as2
					LEFT JOIN xidb.accounts a ON a.id = as2.accid
					LEFT JOIN xidb.chars c ON as2.charid = c.charid
					LEFT JOIN xidb.zone_settings z ON z.zoneid = c.pos_zone
					LEFT JOIN forums.phpbb_users pu ON pu.user_email = a.registration_email)"	=> 's',
		);
		$sql_ary = $event['sql_ary'];
		$sql_ary['FROM'] = $sql_ary_from;
		$event['sql_ary'] = $sql_ary;
	}
	/**
	 * Create an account in the XI database after a forum user has been added
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function xi_create_account($event)
	{
		// cp_data, user_id, user_row
		global $db;

		$xi_pass = strtoupper(sha1(sha1($event['data']['new_password'], true)));
		$xi_pass = '*' . $xi_pass;

		$xi_data = [
			'login' 		=> $event['data']['username'],
			'password'		=> $xi_pass,
			'current_email'		=> $event['data']['email'],
			'registration_email'	=> $event['data']['email'],
			'timecreate'		=> date("Y-m-d H:i:s"),
			'timelastmodify'	=> date("Y-m-d H:i:s"),
			'status'		=> 0,
		];
		$sql = 'INSERT INTO xidb.accounts ' . $db->sql_build_array('INSERT', $xi_data);
		$db->sql_query($sql);
	}

	/**
	 * Activate an account in the XI database after a forum user verified their account
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function xi_activate_account($event)
	{
		global $db;

		$xi_data = [
			'status'	=> 1
		];
		$sql = 'UPDATE xidb.accounts
			SET ' . $db->sql_build_array('UPDATE', $xi_data) . '
			WHERE login = "' . $event['user_row']['username'] . '"';
		$db->sql_query($sql);
		return $db->sql_affectedrows();
	}

	/**
	 * Deactivate an account in the XI database after a forum user has been
	 * banned or deactivated in the forums
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function xi_deactivate_account($event)
	{
		global $db;

		$xi_data = [
			'status'	=> 0
		];
		$sql = 'UPDATE xidb.accounts
			SET ' . $db->sql_build_array('UPDATE', $xi_data) . '
			WHERE login = "' . $event['user_row']['username'] . '"';
		$db->sql_query($sql);
		return $db->sql_affectedrows();
	}

	/**
	 * Update the account password in the XI database when a user changes
	 * their phpBB password
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function xi_update_password($event)
	{
		global $db;
		$new_password = $event['data']['new_password'];
		$password_confirm = $event['data']['password_confirm'];
		if ($new_password != '' && $new_password == $password_confirm) {
			$sql = 'UPDATE xidb.accounts
				SET password = PASSWORD("' . $new_password . '")
				WHERE login = "' . $event['data']['username'] . '"';
			$result = $db->sql_query($sql);
		}
	}

	/**
	 * Add a link to the controller in the forum navbar
	 */
	public function xi_add_page_header_link()
	{
		$this->template->assign_vars([
			'U_LSBBB_PAGE'		=> $this->helper->route('madetoraid_lsbbb_controller_default'),
			'LSBBB_NAV_INDEX'	=> $this->language->lang('LSBBB_NAV_INDEX'),
			'LSBBB_NAV_INSTALL' => $this->language->lang('LSBBB_NAV_INSTALL'),
			'LSBBB_NAV_WORLDMAP' => $this->language->lang('LSBBB_NAV_WORLDMAP'),
			'LSBBB_NAV_AUCTIONHOUSE' => $this->language->lang('LSBBB_NAV_AUCTIONHOUSE'),
			'LSBBB_NAV_CHARACTERS' => $this->language->lang('LSBBB_NAV_CHARACTERS'),
			'LSBBB_NAV_MYLISTINGS' => $this->language->lang('LSBBB_NAV_MYLISTINGS'),
		]);
	}

	/**
	 * Show users viewing lsbBB pages on the Who Is Online page
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function viewonline_page($event)
	{
		//print_r($event['row']);
		if ($event['on_page'][1] === 'app') {
			if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/xi/ah') === 0) {
				$event['location'] = $this->language->lang('LSBBB_VIEWING_AUCTIONHOUSE');
				$event['location_url'] = $this->helper->route('madetoraid_lsbbb_controller_ah');
			} elseif (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/xi/item') === 0) {
				$item_id = substr($event['row']['session_page'], strrpos($event['row']['session_page'], '/' )+1);
				$event['location'] = $this->language->lang('LSBBB_VIEWING_ITEM') . ': ' . $item_id;
				$event['location_url'] = $this->helper->route('madetoraid_lsbbb_controller_item') . $item_id;
			} elseif (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/xi/zone') === 0) {
				$zone_id = substr($event['row']['session_page'], strrpos($event['row']['session_page'], '/' )+1);
				$event['location'] = $this->language->lang('LSBBB_VIEWING_ZONE') . ': ' . $zone_id;
				if($event['row']['session_browser'] == 'FINAL FANTASY XI') { $event['location'] .= ' <i class="fa fa-superpowers" aria-hidden="true"></i>'; }
				$event['location_url'] = $this->helper->route('madetoraid_lsbbb_controller_zone_id', array('zone_id' => $zone_id));
			} elseif (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/xi/char') === 0) {
				$char_id = substr($event['row']['session_page'], strrpos($event['row']['session_page'], '/' )+1);
				$event['location'] = $this->language->lang('LSBBB_VIEWING_CHARACTER') . ': ' . $char_id;
				$event['location_url'] = $this->helper->route('madetoraid_lsbbb_controller_char') . $char_id;
			} elseif (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/xi/npc') === 0) {
				$npc_id = substr($event['row']['session_page'], strrpos($event['row']['session_page'], '/' )+1);
				$event['location'] = $this->language->lang('LSBBB_VIEWING_NPC') . ': ' . $npc_id;
				$event['location_url'] = $this->helper->route('madetoraid_lsbbb_controller_npc_id', array('npc_id' => $npc_id));
			} elseif (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/xi/install') === 0) {
				$page = substr($event['row']['session_page'], strrpos($event['row']['session_page'], '/' )+1);
				$event['location'] = $this->language->lang('LSBBB_VIEWING_INSTALL') . ': ' . $page;
				$event['location_url'] = $this->helper->route('madetoraid_lsbbb_controller_install') . $page;
			} elseif (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/xi') === 0) {
				$event['location'] = $this->language->lang('LSBBB_VIEWING_INDEX');
				$event['location_url'] = $this->helper->route('madetoraid_lsbbb_controller_zone', array('page' => 'index'));
			}
		}
	}

	/**
	 * A sample PHP event
	 * Modifies the names of the forums on index
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function display_forums_modify_template_vars($event)
	{
		$forum_row = $event['forum_row'];
		$forum_row['FORUM_NAME'] .= $this->language->lang('LSBBB_EVENT');
		$event['forum_row'] = $forum_row;
	}
}
