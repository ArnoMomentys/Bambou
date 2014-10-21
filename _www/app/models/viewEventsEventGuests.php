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

    	parent::__construct($db, '_events_eventguests');
    }

    /**
     *
     */
    public function getEventGuestsPaginated($filters, $options)
    {
		$page = \Pagination::findCurrentPage();
		return $this->paginateToArray($page-1, 30, $filters, $options);
    }

    /**
     *
     */
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
