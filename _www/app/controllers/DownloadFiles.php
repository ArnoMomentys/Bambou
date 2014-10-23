<?php


/**
 * 
 */
class DownloadFiles
{

    private $path;
    private $mime;
    private $throttle = 2048; // throttle to around 256 KB /s
    private $force_download = TRUE; // force a dialog box on download


    /**
     * Download a user list csv sample
     */
    public function getSample()
    {
    	$params = F3::get('PARAMS');
    	if(isset($params['dwtype']) && isset($params['eid'])) 
    	{
    		switch ($params['dwtype']) {
    			case 'contacts':
    				$this->path = F3::get('DOWNLOADS').'/contacts.csv';
    				$this->mime = 'text/csv; charset=utf-8';
    				break;
       			case 'bill':
    				$this->path = F3::get('ATTACH').'/facture'.$params['eid'].'.xls';
    				$this->mime = 'application/vnd.ms-excel ; charset=utf-8';
    				break;
    		}
			// send() method returns FALSE if file doesn't exist
    		if (!Web::instance()->send($this->path, $this->mime, $this->throttle, $this->force_download))
    		    // Generate an HTTP 404
    		    F3::error(404);
    	} 
    	else
    	{
    		// Generate an HTTP 404
            F3::error(404);
    	}
    }


    /**
     * Stream csv contact list
     */
    public function exportContacts()
    {
    	$this->mime = 'text/csv; charset=utf-8';
    	$db=new DB\SQL(
    	    F3::get('db_dns') . F3::get('db_name'),
    	    F3::get('db_user'),
    	    F3::get('db_pass')
    	);
        $type = F3::get('POST.ex');
        $eid = F3::get('POST.eid');
        $o_session = json_decode(Encrypt::load()->invert(F3::get('POST.l')));

        // depending on session level,
        // if the request has been loaded by an admin
        // or if the request has been loaded by a host
        if($o_session->lvl<=3 && ($type=='guest' || $type=='host')) 
        {
            // retrieve guests ids
            if($type=='guest')
            {
	            $eventGuests = new viewEventsEventGuests($db);
	            if($o_session->lvl==3)
	            {
    	        	$list = $eventGuests->getGuestsIdsByHost($o_session->uid, $eid);
	            }
	            else 
	            {
	            	$list = $eventGuests->getGuestsIdsByHost(null, $eid);
	            }
            }
            // retrieve hosts ids
            if($type=='host')
            {
	            $eventHosts = new viewEventHostsInfos($db);
	        	$list = $eventHosts->getHostsIdsByEventId($eid);
            }
            // retrieve complete profiles
            $profiles = new viewUserCompleteProfile($db);
            $results = $profiles->getUsersMinimumProfileAliasedByUidsIn_Raw($list);
            $datas = array(
            	'params'=>array('_type'=>$type, 'eid'=>$eid),
            	'results'=>json_encode($results)
            );
            echo json_encode($datas);
        }
        else
        {
            //error
        }
    }


    /**
     * 
     */
    public function bill() {}

}