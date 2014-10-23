<?php

/**
 * viewEventOptions Model
 */
class viewEventOptions extends DB\SQL\Mapper
{

    public function __construct(DB\SQL $db)
    {
        parent::__construct($db, '_event_options');
    }


    /**
     * retrieve all events options
     * @return arrays 	event options filtered by year, events ids
     */
    public function getEventOptions($status = null)
    {
		$results = $this->find(($status == null ? '' : array('`status` = '.$status)), array('order'=>'debut DESC'));
		$groups = $ids = array();
		foreach($results as $event)
		{
			$groups[$event->annee][] = $event->cast();
			$ids[] = $event->eid;
		}
		return array($groups, $ids);
    }


    /**
     * Get all events (not marked as deleted - status < 4) in a list of eids
     * @param  string 	$key    column name
     * @param  array 	$values array of eids
     * @return array    list of events with options and stats
     */
    public function getIn($key, $values)
    {
    	$uid = F3::get('SESSION.uid');
		$results = $this->db->exec('SELECT `eid`,`nom`,`lieu`,`debut`,`fin`,`annee`,`status`,`limitA`,`limitB`,`showContact`,`withSms`,`withRepr`,`withAcc`,`sendType`,`previousEid`,`withBusinessCard`,`cardAddress`,`limitMsg` FROM `_event_options` WHERE `status` < 4 AND eid IN ('.implode(',', $values).') ORDER BY nom DESC');
		$groups = $ids = $invitations = array();
		foreach($results as $event)
		{
			$groups[$event['annee']][] = $event;
			$ids[] = $event['eid'];
		}
		// if I've been invited
		$res_guests = $this->db->exec('SELECT * FROM `_events_eventguests` WHERE eid IN ('.implode(',', $values).') AND guestid = '.$uid);
		$hostinfos = array();
		if(!empty($res_guests))
		{
			foreach($res_guests as $event)
			{
				$invitations[$event['eid']] = $event;
				$query = 'SELECT eventid, hostname FROM `_event_hosts_infos` WHERE eventid='.$event['eid'].' AND hostid='.$event['hostid'];
				$res_hosts = $this->db->exec('SELECT eventid, hostname FROM `_event_hosts_infos` WHERE eventid='.$event['eid'].' AND hostid='.$event['hostid']);
				$invitations[$event['eid']]['hostname'] = $res_hosts[0]['hostname'];
			}
		}

		return array($groups, $ids, $invitations);
    }


    /**
     * Get all events (not marked as deleted - status < 4) in a list of eids
     * @param  string 	$key    column name
     * @param  array 	$values array of eids
     * @return array    list of events with options and stats
     */
    public function getEventByEidIn($aEids, $status = null)
    {
    	$uid = F3::get('SESSION.uid');
		$results = $this->db->exec('SELECT `eid`,`nom`,`lieu`,`debut`,`fin`,`annee`,`status`,`limitA`,`limitB`,`showContact`,`withSms`,`withRepr`,`withAcc`,`sendType`,`previousEid`,`withBusinessCard`,`cardAddress`,`limitMsg` FROM `_event_options` WHERE `status` < 4 AND eid IN ('.implode(',', $aEids).')'.($status == null ? '' : ' AND `status` = '.$status).' ORDER BY `nom` DESC');
		$groups = $ids = $invitations = array();
		foreach($results as $event)
		{
			$groups[$event['annee']][] = $event;
			$ids[] = $event['eid'];
		}
		// if I've been invited
		$res_guests = $this->db->exec('SELECT * FROM `_events_eventguests` WHERE eid IN ('.implode(',', $aEids).') AND guestid = '.$uid);
		$hostinfos = array();
		if(!empty($res_guests))
		{
			foreach($res_guests as $event)
			{
				$invitations[$event['eid']] = $event;
				// $res_hosts = $this->db->exec('SELECT eventid, hostname FROM `_event_hosts_infos` WHERE eventid='.$event['eid'].' AND hostid='.$event['hostid']);
				// $invitations[$event['eid']]['hostname'] = $event['hostname'];
				$invitations[$event['eid']]['hostid'] = $event['hostid'];
			}
		}

		return array($groups, $ids, $invitations);
    }

    
    public function getEventNameByEidIn_Raw($eid) {
    	return $this->db->exec(
    			"SELECT `nom` FROM `_event_options` WHERE `eid` = ?",
    			$eid
    	);
    }


    /**
     * retrieve one event by eid
     * @return object 	event options object
     */
    public function getEventOptionsByEid($eid) {
		$e = new Events($this->db);
	    $read = $e->setLastRead($eid);
    	$event = $this->findone(array('eid=?',$eid));

    	// If NOT Admin
    	if (F3::get('SESSION.lvl') > 2)
    	{
    		$event->withRepr = 0;
    		$event->withAcc = 0;
    	}
    	
    	return $event;
    }


    /**
     * retrieve one event by status less than param
     * @return object 	event options object
     */
    public function getEventOptionsByStatusLessThan($status) {
    	return $this->findone(array('status<?',$status), array('order'=>'eid DESC', 'limit'=>1));
    }

}
