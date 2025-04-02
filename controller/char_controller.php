<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\controller;

use madetoraid\lsbbb\includes\functions_char;
use DateTime;
use DateTimeZone;

/**
 * PHPBB XI Server Integration char controller.
 */
class char_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var char */
	protected $char;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config		Config object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\language\language	$language	Language object
	 * @param \phpbb\user\user			$user		User object

	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language, \phpbb\user $user)
	{
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
		$this->user 	= $user;

		$this->char		= new functions_char();
	}

	/**
	 * Controller handler for route /char/{name}
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($char_id = 0)
	{
		$this->language->add_lang('common', 'madetoraid/lsbbb');
		$this->language->add_lang('zone', 'madetoraid/lsbbb');

		$page_title = $this->language->lang('LSBBB_CHARACTERS');

		$lsbbb_url = generate_board_url() . '/ext/madetoraid/lsbbb';
		$this->template->assign_vars(array(
			'LSBBB_URL' 		=> $lsbbb_url,
			'LSBBB_ITEM_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_item'),
			'LSBBB_AH_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_ah'),
			'LSBBB_ZONE_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_zone'),
			'LSBBB_MOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_mob_group'),
			'LSBBB_CHAR_URL'	=> $this->helper->route('madetoraid_lsbbb_controller_char'),
			'LSBBB_JOB_URL'		=> $this->helper->route('madetoraid_lsbbb_controller_job'),
		));

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_PAGE'),
			'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_default')),
		));
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' => $this->language->lang('LSBBB_CHARACTERS'),
			'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_char')),
		));
		if ($char_id > 0) {
			$char_data = $this->char->xi_char_details($char_id);
			if (isset($char_data['charname'])) {
				$linkshell_data = $this->char->xi_char_linkshells($char_id);
				$equip_data = $this->char->xi_char_equipment($char_id);
				$char_records = $this->char->char_bcnm_records($char_data['charname']);
				$char_bazaar = $this->char->char_bazaar_items($char_id);
				$skillstemp = explode(',', $char_data['skills']);
				foreach ($skillstemp as $s) {
					$k = explode('|', $s);
					@$skill_data[$k[0]] = $k[1] / 10;
				}
				@$char_data['guild_fishing'] = $skill_data[48] | "0";
				@$char_data['guild_woodworking'] = $skill_data[49] | "0";
				@$char_data['guild_smithing'] = $skill_data[50] | "0";
				@$char_data['guild_goldsmithing'] = $skill_data[51] | "0";
				@$char_data['guild_weaving'] = $skill_data[52] | "0";
				@$char_data['guild_leathercraft'] = $skill_data[53] | "0";
				@$char_data['guild_bonecraft'] = $skill_data[54] | "0";
				@$char_data['guild_alchemy'] = $skill_data[55] | "0";
				@$char_data['guild_cooking'] = $skill_data[56] | "0";
				@$char_data['primaryls_name'] = $linkshell_data[0]['name'];
				@$char_data['primaryls_color'] = '#' . strrev(str_replace('f', '', dechex($linkshell_data[0]['color'])));
				@$char_data['primaryls_message'] = $linkshell_data[0]['message'];
				@$char_data['secondaryls_name'] = $linkshell_data[1]['name'];
				@$char_data['secondaryls_color'] = '#' . strrev(str_replace('f', '', dechex($linkshell_data[1]['color'])));
				@$char_data['secondaryls_message'] = $linkshell_data[1]['message'];
				@$char_data['mainid'] = $equip_data[0]['itemid'];
				@$char_data['mainname'] = $equip_data[0]['name'];
				@$char_data['subid'] = $equip_data[1]['itemid'];
				@$char_data['subname'] = $equip_data[1]['name'];
				@$char_data['rangeid'] = $equip_data[2]['itemid'];
				@$char_data['rangename'] = $equip_data[2]['name'];
				@$char_data['ammoid'] = $equip_data[3]['itemid'];
				@$char_data['ammoname'] = $equip_data[3]['name'];
				@$char_data['headid'] = $equip_data[4]['itemid'];
				@$char_data['headname'] = $equip_data[4]['name'];
				@$char_data['neckid'] = $equip_data[9]['itemid'];
				@$char_data['neckname'] = $equip_data[9]['name'];
				@$char_data['ear1id'] = $equip_data[11]['itemid'];
				@$char_data['ear1name'] = $equip_data[11]['name'];
				@$char_data['ear2id'] = $equip_data[12]['itemid'];
				@$char_data['ear2name'] = $equip_data[12]['name'];
				@$char_data['bodyid'] = $equip_data[5]['itemid'];
				@$char_data['bodyname'] = $equip_data[5]['name'];
				@$char_data['handsid'] = $equip_data[6]['itemid'];
				@$char_data['handsname'] = $equip_data[6]['name'];
				@$char_data['ring1id'] = $equip_data[13]['itemid'];
				@$char_data['ring1name'] = $equip_data[13]['name'];
				@$char_data['ring2id'] = $equip_data[14]['itemid'];
				@$char_data['ring2name'] = $equip_data[14]['name'];
				@$char_data['backid'] = $equip_data[15]['itemid'];
				@$char_data['backname'] = $equip_data[15]['name'];
				@$char_data['waistid'] = $equip_data[10]['itemid'];
				@$char_data['waistname'] = $equip_data[10]['name'];
				@$char_data['legsid'] = $equip_data[7]['itemid'];
				@$char_data['legsname'] = $equip_data[7]['name'];
				@$char_data['feetid'] = $equip_data[8]['itemid'];
				@$char_data['feetname'] = $equip_data[8]['name'];
				$page_title .= ' - ' . $char_data['charname'];
				$this->template->assign_block_vars('chardata', $char_data);
				$this->template->assign_block_vars('linkshelldata', $linkshell_data);
				foreach($char_records as $record) {
					$record['zone_name'] = $this->language->lang($record['zone_name']);
					$record['zone_url'] = $this->helper->route('madetoraid_lsbbb_controller_zone_id', array('zone_id' => $record['zoneid']));
					$this->template->assign_block_vars('recordrow', $record);
				}
				foreach($char_bazaar as $bazaar) {
					$bazaar['item_img'] = $this->helper->route('madetoraid_lsbbb_controller_item_id', array('item_id' => $bazaar['itemid']));
					$bazaar['item_url'] = $this->helper->route('madetoraid_lsbbb_controller_item_id', array('item_id' => $bazaar['itemid']));
					$this->template->assign_block_vars('bazaarrow', $bazaar);
				}
				$this->template->assign_vars(array('charprofile' => true));
				$this->template->assign_block_vars('navlinks', array(
					'FORUM_NAME' => $char_data['charname'],
					'U_VIEW_FORUM' => append_sid($this->helper->route('madetoraid_lsbbb_controller_char') . $char_id),
				));
			}
			else {
				redirect($this->helper->route('madetoraid_lsbbb_controller_char'));
			}
		} else {
			$char_data = $this->char->xi_char_search();
			if (sizeof($char_data) > 0) {
				foreach ($char_data as $char_row) {
					$char_row['zone_name'] = $this->language->lang($char_row['zone_name']);
					$char_row['zone_url'] = $this->helper->route('madetoraid_lsbbb_controller_zone_id', array('zone_id' => $char_row['zone_id']));

					$updatetimezone = new DateTimeZone('UTC');
					$updatetime = new DateTime($char_row['lastupdate'], $updatetimezone);
					$updatetime->setTimezone($this->user->timezone);
					//$char_row['lastupdate'] = $updatetime->format('D M j, Y g:i a');
					$char_row['lastupdate'] = $updatetime->format('Y-m-d H:i:s T');
					$this->template->assign_block_vars('charrow', $char_row);
				}
			} else {
				$page_extras = false;
				$page_title = $this->language->lang('LSBBB_CHARACTER_NOT_FOUND');
			}
			$this->template->assign_vars(array('charprofile' => false));
		}
		page_header($page_title);
		return $this->helper->render('@madetoraid_lsbbb/xi_char_body.html', $char_id);
	}
}
