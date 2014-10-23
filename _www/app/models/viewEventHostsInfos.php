<?php

/**
 * viewEventHostsInfos Model
 */
class viewEventHostsInfos extends MyMapper {


    public function __construct(DB\SQL $db) {
        parent::__construct($db, '_event_hosts_infos');
    }


    public function getHostsByEventIdFiltered_Paginated( $filters, $options ) {
		$page = \Pagination::findCurrentPage();
		return $this->paginateToArray($page-1, 30, $filters, $options);
    }


    public function getHostsIdsByEventId($eid) {
		$aHosts = $this->find(array('eventid=?', $eid), array('order'=>'hostname ASC'));
		$hosts = array();
		foreach($aHosts as $host)
		{
			$hosts[] = $host->hostid;
		}
		return $hosts;
    }


    public function getEventIDsByHostIDGroupByEventID($hostid) {
		$count_events = $this->count(array('hostID=?', $hostid), array('group'=>'eventID'));
		$aeids = [];
		if($count_events > 0) {
    		$events_select = $this->select('eventid',array('hostID=?', $hostid), array('group'=>'eventID'));
            foreach($events_select as $event) {
                $aeids[] = $event->eventid;
            }
            return $aeids;
        }
        return [];
    }


    public function getHostEvents($hostID) {
    	return $this->find(array('hostID=?',$hostID));
    }


    public function getHostsInvitationsByEventid($eid) {
		$invitations = $this->db->exec(
			'SELECT hostID, nbGuestsTotalPerHost AS nbGuestsTotal FROM _host_stats_guests_total WHERE eid = ?',
			$eid
		);
		foreach($invitations as $invitation)
			$a[$invitation['hostID']] = $invitation['nbGuestsTotal'];

		return $a;
    }

}
