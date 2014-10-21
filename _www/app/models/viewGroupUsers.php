<?php

/**
 * viewGroupUsers Model
 */
class viewGroupUsers extends DB\SQL\Mapper {


    public function __construct(DB\SQL $db) {
        parent::__construct($db, '_group_stats_uids');
    }


    public function getGroupListFiltered_Paginated($filters, $options) {
		$page = \Pagination::findCurrentPage();
		return $this->paginateToArray($page-1, 30, $filters, $options);
    }


    public function getGroupListAndStats_Raw() {
    	return $this->db->exec('SELECT * FROM `_group_stats_uids`');
    }


    public function getGroupStatByGid_Raw($gid) {
    	return $this->db->exec('SELECT * FROM `_group_stats_uids` WHERE `gid`=?', $gid);
    }

}
