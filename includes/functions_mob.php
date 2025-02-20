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
class functions_mob
{

    public function __construct()
    {
        $this->xi    = new functions_xi();
    }

    public function xi_mob_info(int $mob_id)
    {
        global $db;
        $sql_array = array(
            'SELECT'    => 'FLOOR(msp.mobid/4096)-4096 zoneid, msp.mobid, msp.groupid, msp.polutils_name, zs.name zone, msp.pos_x, msp.pos_y, msp.pos_z, mg.name groupname',
            'FROM'        => array(
                'xidb.mob_spawn_points' => 'msp',
            ),
            'LEFT_JOIN'    => array(
                array(
                    'FROM'    => array('xidb.zone_settings' => 'zs'),
                    'ON'    => 'zs.zoneid = FLOOR(msp.mobid/4096)-4096',
                ),
                array(
                    'FROM'    => array('xidb.mob_groups' => 'mg'),
                    'ON'    => 'mg.zoneid = zs.zoneid AND mg.groupid = msp.groupid',
                ),
            ),
            'WHERE'        => 'msp.mobid = ' . (int) $mob_id,
        );
        $sql = $db->sql_build_query('SELECT', $sql_array);

        $result = $db->sql_query($sql);
        $mob_info = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);

        $return = array(
            'id'        => $mob_info['mobid'],
            'hex'       => dechex($mob_info['mobid']),
            'name'      => $mob_info['polutils_name'],
            'zone'      => $mob_info['zone'],
            'groupid'   => $mob_info['groupid'],
            'groupname' => $mob_info['groupname'],
            'zoneid'    => $mob_info['zoneid'],
            'pos_x'     => $mob_info['pos_x'],
            'pos_y'     => $mob_info['pos_y'],
            'pos_z'     => $mob_info['pos_z'],
            //'familyid' => $mob['familyid'],
            //'family' => $mob['family'],
            //'mjob' => $mob['mjob'],
            //'sjob' => $mob['sjob'],
            //'aggro' => $mob['aggro'],
            //'detects' => $detects,
            //'true_detection' => $mob['true_detection'],
            //'links' => $mob['links'],
            //'mobtype' => $mob['mobtype'],
            //'flag' => $mob['flag'],
            //'respawntime' => $mob['respawntime'],
            //'spawntype' => $mob['spawntype'],
            //'minlevel' => $mob['minlevel'],
            //'maxlevel' => $mob['maxlevel'],
        );

        return ($return);
    }

    /**
     * mob drop info
     */
    public function xi_mob_group_info(int $zone_id, int $group_id)
    {
        global $db;
        $return = array();

        $sql_array = array(
            'SELECT'    => 'mg.groupid, mg.name, mg.zoneid, mp.familyid, mp.mjob, mp.sjob, mp.aggro, mp.true_detection, mp.links, mp.mobtype, mp.flag, mg.respawntime, mg.spawntype, mg.minlevel, mg.maxlevel, z.name zone, mfs.family family, mfs.detects',
            'FROM'        => array(
                'xidb.mob_groups' => 'mg',
            ),
            'LEFT_JOIN'    => array(
                array(
                    'FROM'    => array('xidb.mob_pools' => 'mp'),
                    'ON'    => 'mp.poolid = mg.poolid',
                ),
                array(
                    'FROM'    => array('xidb.mob_family_system' => 'mfs'),
                    'ON'    => 'mfs.familyid = mp.familyid',
                ),
                array(
                    'FROM'    => array('xidb.zone_settings' => 'z'),
                    'ON'    => 'mg.zoneid = z.zoneid',
                ),
            ),
            'WHERE'        => 'mg.groupid = ' . (int) $group_id . ' and mg.zoneid = ' . (int) $zone_id,
        );
        $sql = $db->sql_build_query('SELECT', $sql_array);


        $result = $db->sql_query($sql);
        $mob_data = (array) $db->sql_fetchrowset($result);
        $db->sql_freeresult($result);

        foreach ($mob_data as $mob) {

            $detects = array();
            if ($mob['detects'] & 1) {
                $detects[] = array('detect' => 'Sight', 'class' => 'sight');
            }
            if ($mob['detects'] & 2) {
                $detects[] = array('detect' => 'Sound', 'class' => 'sound');
            }
            if ($mob['detects'] & 4) {
                $detects[] = array('detect' => 'Low HP', 'class' => 'lowhp');
            }
            if ($mob['detects'] & 32) {
                $detects[] = array('detect' => 'Magic', 'class' => 'magic');
            }

            $return[] = array(
                'name' => $this->xi->xi_ucwords($mob['name']),
                'zone' => str_replace('_', ' ', $mob['zone']),
                'groupid' => $mob['groupid'],
                'zoneid' => $mob['zoneid'],
                'familyid' => $mob['familyid'],
                'family' => $mob['family'],
                'mjob' => $mob['mjob'],
                'sjob' => $mob['sjob'],
                'aggro' => $mob['aggro'],
                'detects' => $detects,
                'true_detection' => $mob['true_detection'],
                'links' => $mob['links'],
                'mobtype' => $mob['mobtype'],
                'flag' => $mob['flag'],
                'respawntime' => $mob['respawntime'],
                'spawntype' => $mob['spawntype'],
                'minlevel' => $mob['minlevel'],
                'maxlevel' => $mob['maxlevel'],
            );
        }

        return ($return);
    }

    /**
     * mob skills
     */
    public function xi_mob_group_spawns(int $zone_id, int $group_id)
    {
        global $db;
        // SELECT FLOOR(msp.mobid/4096)-4096 zoneid, msp.mobid, msp.groupid FROM xidb.mob_spawn_points msp;
        $sql_array = array(
            'SELECT'    => 'FLOOR(msp.mobid/4096)-4096 zoneid, msp.mobid, HEX(msp.mobid) mobhex, msp.groupid, msp.polutils_name, zs.name zone, msp.pos_x, msp.pos_y, msp.pos_z',
            'FROM'        => array(
                'xidb.mob_spawn_points' => 'msp',
            ),
            'LEFT_JOIN'     => array(
                array(
                    'FROM'  => array('xidb.zone_settings' => 'zs'),
                    'ON'    => 'zs.zoneid = FLOOR(msp.mobid/4096)-4096',
                ),
            ),
            'WHERE'         => 'FLOOR(msp.mobid/4096)-4096 = ' . (int) $zone_id . ' AND msp.groupid = ' . (int) $group_id,
        );
        $sql = $db->sql_build_query('SELECT', $sql_array);


        $result = $db->sql_query($sql);
        $mob_spawns = (array) $db->sql_fetchrowset($result);
        $db->sql_freeresult($result);

        return ($mob_spawns);
    }

    /**
     * mob drop list
     */
    public function xi_mob_drops(int $zone_id, int $group_id)
    {
        global $db;
        $return = array();

        $sql_array = array(
            'SELECT'    => 'md.dropid, md.droptype, md.groupid, md.grouprate, md.itemid, md.itemrate, ib.name, ib.sortname',
            'FROM'        => array(
                'xidb.mob_droplist' => 'md',
            ),
            'LEFT_JOIN'    => array(
                array(
                    'FROM'    => array('xidb.item_basic' => 'ib'),
                    'ON'    => 'md.itemid = ib.itemid',
                ),
            ),
            'WHERE'        => 'md.dropid = (SELECT mg.dropid FROM xidb.mob_groups mg WHERE mg.groupid = ' . (int) $group_id . ' and mg.zoneid = ' . (int) $zone_id . ')',
            'ORDER_BY'      => 'ib.itemid'
        );
        $sql = $db->sql_build_query('SELECT', $sql_array);


        $result = $db->sql_query($sql);
        $drop_data = (array) $db->sql_fetchrowset($result);
        $db->sql_freeresult($result);

        foreach ($drop_data as $key => $drop) {
            $return[] = array(
                'dropid' => $drop['dropid'],
                'grouprate' => $drop['grouprate'] / 10,
                'itemid' => $drop['itemid'],
                'itemrate' => $drop['itemrate'] / 10,
                'name' => $this->xi->xi_ucwords($drop['name']),
                'sortname' => $this->xi->xi_ucwords($drop['sortname']),
                'rownum' => $key,
            );
        }

        return ($return);
    }

    /**
     * mob skills
     */
    public function xi_mobskills($name)
    {
        global $db;
        $sql_array = array(
            'SELECT'    => '*',
            'FROM'        => array(
                'xidb.mob_skill_lists' => 'msl',
            ),
            'LEFT_JOIN'    => array(
                array(
                    'FROM'    => array('xidb.mob_skills' => 'ms'),
                    'ON'    => 'ms.mob_skill_id = msl.mob_skill_id',
                ),
            ),
            'WHERE'        => 'msl.skill_list_name = "' . $name . '"'
        );
        $sql = $db->sql_build_query('SELECT', $sql_array);


        $result = $db->sql_query($sql);
        $skill_data = (array) $db->sql_fetchrowset($result);
        $db->sql_freeresult($result);

        return ($skill_data);
    }
}
