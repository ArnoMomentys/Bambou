<?php

/**
 * viewEventsEventGuests Model
 */
class viewEventsEventGuests extends MyMapper
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
        //echo '<pre>'.print_r($filters).'</pre>'; die();
		$page = \Pagination::findCurrentPage();
		$result = $this->paginateToArray($page-1, 30, $filters, $options);
		
		$result['subset'] = $this->cleanDataArraySqlToPHP($result['subset']);
		
		//echo '<pre>'.print_r ($result, true).'</pre>'; die("ee");
		
		return $result;
    }

    /**
     *
     */
    public function getHostsList($guestId, $filters) 
    {
    	$hostName = [];
    	$sql = "select hostid, hostname from _events_eventguests where guestid=? and eid=?";
    	$params = array(1 => $guestId, 2 => $filters[1]);
		
    	$results = $this->db->exec($sql,$params);
    	foreach($results as $host){
			$hostName[] = $host;
    	}
    	
    	return $hostName;	 
    }

    
    
    
    
    public function getGuestsByHost($hostid, $eventid)
    {
		$filters = array(MyMapper::getArraySqlField("hostid")." AND eid=?", MyMapper::getArraySqlRegexp($hostid), $eventid);
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
    		$filters = array(MyMapper::getArraySqlField("hostid")." AND eid=?", MyMapper::getArraySqlRegexp($hostid), $eventid);

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
