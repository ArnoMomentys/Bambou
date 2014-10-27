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
		
		//$data = $this->db->log();
		//echo '<pre>'.print_r ($result, true).'</pre>';
		$sqlArrayFieldPrefixLength = strlen(Controller::SQL_ARRAY_FIELD_PREFIX);
		foreach($result['subset'] as $iRow => $guestData) 
		{
		    // Check if sql field are array
			foreach ($guestData as $key => $currentGuestData)
			{
			    if (substr($key, 0, $sqlArrayFieldPrefixLength) == Controller::SQL_ARRAY_FIELD_PREFIX)
			    {
			        $newKey = substr($key, $sqlArrayFieldPrefixLength);
			        
			        // The field is an array
			        $result['subset'][$iRow][$newKey] = explode(Controller::SQL_ARRAY_DELIMITER, $currentGuestData);
			        
			        // Delete the raw result
			        unset($result['subset'][$iRow][$key]);
			    }    
			}
			
			// 
			$result['subset'][$iRow]['invitationids'] = implode(',', $result['subset'][$iRow]['invitationid']);
		}
		
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
