<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

use madetoraid\lsbbb\includes\functions_xi;

/**
 * mob functions
 */
class functions_npc
{

	/** @var xi */
	protected $xi;

    public function __construct()
    {
        $this->xi    = new functions_xi();
    }

    public function xi_npc_info(int $npc_id)
    {
        global $db;
        $return = array();

        $sql_array = array(
            'SELECT'    => 'nl.npcid, nl.name, nl.polutils_name, pos_rot, pos_x, pos_y, pos_z, flag, status, entityflags, content_tag, widescan, zs.name zone, zs.zoneid',
            'FROM'        => array(
                'xidb.npc_list' => 'nl',
            ),
            'LEFT_JOIN'    => array(
                array(
                    'FROM'    => array('xidb.zone_settings' => 'zs'),
                    'ON'    => 'zs.zoneid = FLOOR((nl.npcid - 16777216)/4096)',
                ),
            ),
            'WHERE'        => 'nl.npcid = ' . (int) $npc_id,
        );
        $sql = $db->sql_build_query('SELECT', $sql_array);


        $result = $db->sql_query($sql);
        $npc_data = (array) $db->sql_fetchrow($result);
        $db->sql_freeresult($result);

        $return = $npc_data;

        return ($return);
    }
}
