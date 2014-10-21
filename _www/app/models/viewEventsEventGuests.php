<?php

/**
 * viewEventsEventGuests Model
 */
class viewEventsEventGuests extends DB\SQL\Mapper
{
    /**
     *
     */
    public function __construct(DB\SQL $db)
    {

    	parent::__construct($db, '_events_eventguests_nohosts');
    	$this->db = $db;
    }

    /**
     *
     */
    public function getEventGuestsPaginated($filters, $options)
    {
		$page = \Pagination::findCurrentPage();
		$result = $this->paginateToArray($page-1, 30, $filters, $options);
		foreach($result['subset'] as $key=>$guest) {
			$result['subset'][$key]['hostname'] = $this->getHostsList($guest['guestid'], $filters);
		}
		return $result;
    }

    /**
     *
     */
    public function getHostsList($guestId, $filters) 
    {
    	$hostName = "";
    	$sql = "select hostname from _events_eventguests where guestid=? and ".$filters[0];
    	$params = array(1 => $guestId, 2 => $filters[1]);
    	//var_dump($params);die;
    	$results = $this->db->exec("select hostname from _events_eventguests where guestid=? and ".$filters[0],$params);
    	foreach($results as $host){
    		$hostName .= " ".$host['hostname'];
    	}
    	return $hostName;	 
    }

    
    
    
    
    public function getGuestsByHost($hostid, $eventid)
    {
		$filters = array("hostid=? AND eid=?", $hostid, $eventid);
		$results = $this->find($filters, array('order'=>'guestname ASC'));
		$guests = array();
		foreach($results as $guest)
			$guests[] = $guest->guestid;

		return $guests;
    }

    /**
     *
     */
    public function getGuestsIdsByHost($hostid, $eventid)
    {
    	if($hostid === null)
    		$filters = array("eid=?", $eventid);
    	else
    		$filters = array("hostid=? AND eid=?", $hostid, $eventid);

		$results = $this->find($filters, array('order'=>'guestname ASC'));
		$guests = array();
		foreach($results as $guest)
			$guests[] = $guest->guestid;

		return $guests;
    }

    /**
     *
     */
    public function getEventGuestsAccompanying_Raw($invitationID)
    {

    }

    /**
     *
     */
    public function getEventGuestsRepresentative_Raw($invitationID)
    {

    }

}
