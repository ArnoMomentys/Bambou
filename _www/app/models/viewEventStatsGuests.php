<?php

/**
 * viewEventStatsGuests Model
 */
class viewEventStatsGuests extends MyMapper {

	public function __construct($db)
    {

    	$this->db = $db;
    }

    public function show($filter=null)
    {
    	$filter = $filter && filter_var($filter, FILTER_VALIDATE_INT) ? 'e.eid='.$filter : '1=1';
       	$sql = "SELECT e.eid, 
       	            COALESCE(NULL,`gst`.`nbGuestsTotal`,0) AS `nbGuestsTotal`, 
               	    COALESCE(NULL,`gsay`.`nbGuestsAnswerYes`,0) AS `nbGuestsAnswerYes`, 
               	    COALESCE(NULL,`gsann`.`nbGuestsAnswerNone`,0) AS `nbGuestsAnswerNone`, 
               	    COALESCE(NULL,`gsan`.`nbGuestsAnswerNo`,0) AS `nbGuestsAnswerNo`,
       	            COALESCE(NULL,`gspy`.`nbGuestsPresenceYes`,0) AS `nbGuestsPresenceYes`, 
               	    COALESCE(NULL,`gspnn`.`nbGuestsPresenceNone`,0) AS `nbGuestsPresenceNone`, 
               	    COALESCE(NULL,`gspn`.`nbGuestsPresenceNo`,0) AS `nbGuestsPresenceNo`,  
               	    COALESCE(NULL,`gsa`.`nbGuestsAcc`,0) AS `nbGuestsAcc`, 
               	    COALESCE(NULL,`gsapy`.`nbGuestsAccPresenceYes`,0) AS `nbGuestsAccPresenceYes`, 
               	    COALESCE(NULL,`hst`.`nbHostsTotal`,0) AS `nbHostsTotal`, 
               	    count(`inv`.`iid`) AS `nbInvitations`, 
               	    COALESCE(NULL,`iv`.`validated`,0) AS `nbInvValidated`, 
               	    COALESCE(NULL,`ise`.`invitationSent`,0) AS `nbInvSent` 
                FROM events e 
               	LEFT JOIN _guests_stats_total gst on e.eid = gst.eid
               	LEFT JOIN _guests_stats_answer_yes gsay on e.eid = gsay.eid
               	LEFT JOIN _guests_stats_answer_none gsann on e.eid = gsann.eid
               	LEFT JOIN _guests_stats_answer_no gsan on e.eid = gsan.eid
       	        LEFT JOIN _guests_stats_presence_yes gspy on e.eid = gspy.eid
               	LEFT JOIN _guests_stats_presence_none gspnn on e.eid = gspnn.eid
               	LEFT JOIN _guests_stats_presence_no gspn on e.eid = gspn.eid
               	LEFT JOIN _guests_stats_accompanying gsa on e.eid = gsa.eid
               	LEFT JOIN _guests_stats_accompanying_presence_yes gsapy on e.eid = gsapy.eid
               	LEFT JOIN _hosts_stats_total hst on e.eid = hst.eid
               	LEFT JOIN invitations inv on e.eid = inv.eventID
               	LEFT JOIN _invitations_validated iv on e.eid = iv.eventID
               	LEFT JOIN _invitations_sent ise on e.eid = ise.eventID
                WHERE ".$filter."
                GROUP BY e.eid ";
       	
       	//echo $sql; die();
       	
       	$res = $this->db->exec($sql);
       	
		$c = 0;
		$stats = array();
		foreach($res as $row)
		{
		  	$stats[$c] = (object) $row;
		  	$stats[$c] = MyMapper::addStatsArray($stats[$c]);
		  	$c++;
		}
		
		return $stats;
    }

}
