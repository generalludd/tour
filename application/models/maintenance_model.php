<?php defined('BASEPATH') OR exit('No direct script access allowed');

// maintenance_model.php Chris Dart Jan 6, 2014 9:45:47 PM chrisdart@cerebratorium.com

/*
 * perform maintenance activities on the database. These should not be needed.
 * The controllers and models should not allow this to happen. But unti those can be
 * cleared up, this is the solution.
 */

class Maintenance_model extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    /**
     * cleanup function that removes all orphaned addresses (ones that are no longer connected to any person)
     */
    function remove_orphan_addresses()
    {
        $query = "DELETE `ad`.*  FROM `address` AS `ad` LEFT JOIN `person` ON `ad`.`id` = `person`.`address_id` WHERE `person`.`address_id` IS NULL";
        $this->db->query($query);
    }

    function remove_orphan_tourists()
    {
        $query = "DELETE `t`.*  FROM `tourist` AS `t` LEFT JOIN `person` ON `t`.`person_id` = `person`.`id` WHERE `person`.`id` IS NULL";
        $this->db->query($query);

    }

    function remove_orphan_payers()
    {
        $query = "DELETE `p`.*  FROM `payer` AS `p` LEFT JOIN `person` ON `p`.`payer_id` = `person`.`id` WHERE `person`.`id` IS NULL";
        $this->db->query($query);
    }


}