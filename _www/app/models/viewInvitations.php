<?php

/**
 * viewInvitations Model
 */
class viewInvitations extends MyMapper {


    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'invitations');
    }


    public function getEventIDsByGuestIDGroupByEventID($guestid) {
		$count_invitations = $this->count(array('guestID=?', $guestid), array('order'=>'eventID ASC'));
		$aeids = [];
		if($count_invitations > 0) {
    		$invitations_select = $this->select('eventID',array('guestID=?', $guestid), array('order'=>'eventID ASC'));
            foreach($invitations_select as $invitation) {
                $aeids[] = $invitation->eventID;
            }
            return $aeids;
        }
        return [];
    }


}
