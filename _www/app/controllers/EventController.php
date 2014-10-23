<?php

/**
 * Event controller class
 */
class EventController extends AuthController {

	/**
	 * Retrieve all events and their options
	 *
	 * Instanciate a viewEventOS mapper
	 * load getall method to retrieve every events and their options
	 * set variables for the template
	 *
	 * @return [type] [description]
	 */
	public function showAll() {
		$this->show();
	}
	
	public function showActive() {
		$this->show(1);
	}
	
	public function showArchived() {
		$this->show(3);
	}
	
	public function show($status = null)
	{

		$events['hosted'] = $events['invited'] = [];
		$eventOptions = new viewEventOptions($this->db);
		if( $this->f3->get('SESSION.lvl')==1 )
		{
			// je suis admin
			$subset = $eventOptions->getEventOptions($status);
		}
		else
		{
			// je suis invité | invitant
			$invitations = new viewInvitations($this->db);
			$eventsImInvited = $invitations->getEventIDsByGuestIDGroupByEventID($this->f3->get('SESSION.uid'));
			$events['invited'] = $eventsImInvited;
			$eventhosts = new viewEventHostsInfos($this->db);
			$eventsImHost = $eventhosts->getEventIDsByHostIDGroupByEventID($this->f3->get('SESSION.uid'));
			$events['hosted'] = $eventsImHost;

			$merged_events = array_unique(array_merge($events['invited'], $events['hosted']));
			$subset = $eventOptions->getEventByEidIn($merged_events, $status);
		}
		// events I participated
		$sets = $subset[0];

		// tri par année de la plus récente à la plus ancienne
		krsort($sets);
		$idx = $subset[1];

		// if i have been invited
		if(isset($subset[2]))
		{
			$events['invitations'] = $subset[2];
		}

		$stats = array();
		if( $this->f3->get('SESSION.lvl') <= 2 )
		{
			$stats = $this->calculateStatsAdmin();
		}
		elseif( $this->f3->get('SESSION.lvl') == 3 )
		{
			$stats = $this->calculateStatsHost();
		}

		foreach( $idx as $i ) {
			if( !array_key_exists($i, $stats) ) {
				$stats[$i] = new stdClass();
				$stats[$i]->eid = (int) $i;
				$stats[$i]->nbGuestsTotal = 0;
				$stats[$i]->nbGuestsPresence = 0;
				$stats[$i]->nbGuestsAnswerYes = 0;
				$stats[$i]->nbGuestsAnswerNone = 0;
				$stats[$i]->nbGuestsAnswerNo = 0;
				$stats[$i]->nbGuestsAcc = 0;
				$stats[$i]->nbGuestsAccPresenceYes = 0;
				$stats[$i]->nbInvitations = 0;
				$stats[$i]->nbHostsTotal = 0;
				$stats[$i]->nbInvValidated = 0;
			}
		}

		$this->f3->mset(
			array(
				'lists' => $sets,
				'totaux' => (isset($sets['total']) && $sets['total']>0 ? $sets['total'] : 0),
				'stats' => $stats,
				'allMyEvents' => $events,
				'listtype' => 'events',
				'listindex' => 'eid',
				'page_header' => $this->T('events_list'),
				'listname' => $this->T('events'),
				'date' => date('Y-m-d'),
				'view' => 'event/event_grid.htm'
			)
		);
	}

	/**
	 * Show détails of an event
	 */
	public function showOne()
	{
		$params = (object) array_map('trim', $this->f3->get('PARAMS'));
		$event['hosted'] = $event['invited'] = [];
		$eventsHosted = json_decode($this->f3->get('SESSION.events'));
		if( ( $this->f3->get('SESSION.lvl') <= 3 && in_array($params->eid, $eventsHosted) ) || $this->f3->get('SESSION.lvl') == 1 )
		{
			if( $this->f3->get('SESSION.lvl') == 1 )
			{
				$stats = $this->calculateStatsAdmin($params->eid);
			}
			elseif( $this->f3->get('SESSION.lvl') == 3 )
			{
				$stats = $this->calculateStatsHost($params->eid);
			}
			if( !array_key_exists($params->eid, $stats) )
			{
				$stats[$params->eid] = new stdClass();
				$stats[$params->eid]->eid = (int) $params->eid;
				$stats[$params->eid]->nbGuestsTotal = 0;
				$stats[$params->eid]->nbGuestsPresence = 0;
				$stats[$params->eid]->nbGuestsAnswerYes = 0;
				$stats[$params->eid]->nbGuestsAnswerNone = 0;
				$stats[$params->eid]->nbGuestsAnswerNo = 0;
				$stats[$params->eid]->nbGuestsAcc = 0;
				$stats[$params->eid]->nbGuestsAccPresenceYes = 0;
				$stats[$params->eid]->nbInvitations = 0;
				$stats[$params->eid]->nbHostsTotal = 0;
				$stats[$params->eid]->nbInvValidated = 0;
			}
			$eventOptions = new viewEventOptions($this->db);
			$event_options = $eventOptions->getEventOptionsByEid($params->eid);
			$this->f3->mset(
				array(
					'event' => $event_options,
					'page_header' => $this->T('event_list').' : '.$event_options->nom,
					'stats' => $stats,
					'isold' => ($event_options->limitA > date('Y-m-d') ? false : true),
					'date' => date('Y-m-d'),
					// 'filter' => $filter,
					// 'filtervalue' => $filtervalue,
					// 'search_header' => $this->T('search_host'),
					// 'search_pat' => "/event/$params->eid/show/hosts/hostname/___/order/asc",
					// 'no_search_pat' => "/event/$params->eid/show",
					'view' => 'event/show.htm'
				)
			);
		}
		else
		{
	 	   $this->f3->reroute('/events');
		}
	}

	/**
	 * retrieve all stats of all events
	 * @return array 	stats[eid]=>array of stats
	 */
	private function calculateStatsAdmin($eid=null)
	{

		$sts = new viewEventStatsGuests($this->db);
		$s = $sts->show($eid);
		$stats = array();
		foreach($s as $stat)
			$stats[$stat->eid] = $stat;

		return $stats;
	}

	/**
	 * retrieve all infos for host's stats
	 * @return array 	stats[eid]=>array of stats (total guests, presence, accompanying ...)
	 */
	private function calculateStatsHost($eid=null)
	{

		$stats = $tmp = $queries = array();
		$uid = $this->f3->get('SESSION.uid').($eid!=null?' AND eventID = '.$eid:'');
		$queries[] = $this->db->exec(
			'SELECT eid, nbGuestsAccPerHost AS nbGuestsAcc FROM _host_stats_guests_accompanying WHERE hostID = ?',
			$uid
		);
		$queries[] = $this->db->exec(
			'SELECT eid, nbGuestsAccPresenceYesPerHost AS nbGuestsAccPresenceYes FROM _host_stats_guests_accompanying_presence_yes WHERE hostID = ?',
			$uid
		);
		$queries[] = $this->db->exec(
			'SELECT eid, nbGuestsAnswerNoPerHost AS nbGuestsAnswerNo FROM _host_stats_guests_answer_no WHERE hostID = ?',
			$uid
		);
		$queries[] = $this->db->exec(
			'SELECT eid, nbGuestsAnswerNonePerHost AS nbGuestsAnswerNone FROM _host_stats_guests_answer_none WHERE hostID = ?',
			$uid
		);
		$queries[] = $this->db->exec(
			'SELECT eid, nbGuestsAnswerYesPerHost AS nbGuestsAnswerYes FROM _host_stats_guests_answer_yes WHERE hostID = ?',
			$uid
		);
		$queries[] = $this->db->exec(
			'SELECT eid, nbGuestsPresencePerHost AS nbGuestsPresence FROM _host_stats_guests_presence WHERE hostID = ?',
			$uid
		);
		$queries[] = $this->db->exec(
			'SELECT eid, nbGuestsTotalPerHost AS nbGuestsTotal FROM _host_stats_guests_total WHERE hostID = ?',
			$uid
		);
		$queries[] = $this->db->exec(
			'SELECT eid, nbInvValidated FROM _invitations_validated_per_host WHERE hostID=?',
			$uid
		);

		$dump = array(
			'nbGuestsAcc' => 0,
			'nbGuestsAccPresenceYes' => 0,
			'nbGuestsAnswerNo' => 0,
			'nbGuestsAnswerNone' => 0,
			'nbGuestsAnswerYes' => 0,
			'nbGuestsPresence' => 0,
			'nbGuestsTotal' => 0,
			'nbInvValidated' => 0,
			'nbInvitations' => 0,
			'nbHostsTotal' => 0
		);

		foreach ($queries as $q) {
			foreach ($q as $k => $stat) {
				if(!isset($stats[$stat['eid']])) {
					$stats[$stat['eid']] = $dump;
				}
				$tmp[$stat['eid']] = $stat;
				$stats[$stat['eid']] = array_merge($stats[$stat['eid']], $tmp[$stat['eid']]);
			}
		}

		$s = array();
		foreach ($stats as $key => $value) {
			$s[$key] = (object) $value;
		}

		return $s;
	}

	/**
	 * Create an event and its options
	 *
	 * Reset errors array
	 * check if there is a POST
	 *		store post datas in variable
	 * 		perform a validation on each input
	 *		// validation is made here because we don't need to convert datas as of event options creation
	 *		// and if datas aren't ok, no need to create an event object mapper
	 *			set the i18n text for error
	 *			collect validation test
	 *			check if input is a date
	 *				set the i18n text for format
	 *				collect format validation test
	 * 		store validation results
	 *		check if no error in results
	 * 			instanciate object mapper
	 *			add post datas to object and insert into events table
	 *			reroute to event options form by passing new event's eid
	 *		if error in results
	 *			set errors to display
	 *			set valid datas to be displayed inside corresponding valid inputs
	 *			set view to display the event creation form
	 * reset form errors
	 * reset valid datas
	 * set view to display the event creation form
	 */
    public function create()
    {
    	if( $this->f3->get('SESSION.lvl') <= 2 )
    	{
	        if( $this->f3->exists('POST.create') )
	        {
	        	$post = array_map('trim', $this->f3->get('POST'));
	            $check = new Test(1);
	            $errors = array();
	            foreach($post as $post_index => $post_value) {
	            	$t_text = 'event_'.$post_index.'_required';
		            $check->expect(strlen($post_value)===0, $t_text);
		            if(in_array($post_index, array('debut','fin','limitA','limitB','deadLine'))) {
		            	$t_date = 'event_'.$post_index.'_date_format';
		            	$check->expect(strtotime($post_value)===false, $t_date);
		            }
	            }
	            $r = $check->results();
	            if(empty($r)) {
	            	$event = new Events($this->db);
		            $event->add();
		            $this->f3->reroute('/event/'.$event->eid.'/create/options');
	            } else {
	            	while (list(, $value) = each($r)) {
	            		list($e, $i, $x) = explode('_', $value['text']);
	            		$this->errors($this->T($value['text']));
	            	}
	            	$this->f3->set('post_has_data', $post);
					$this->f3->set('view', 'event/form/create.htm');
	            }
	        } else {
	            $this->f3->set('post_has_data', '');
	            $this->f3->set('view', 'event/form/create.htm');
	        }
	    }
	    else
	    {
	 	   $this->f3->reroute('/events');
	    }
    }

    /**
     * Create options for an event
     * Unlike event form validation, datas here are not required and don't need to be validated
     * So we can map it directly to an object mapped to eventOptions table
     * We just need to convert the on/off statuses to 0/1 inside the mapper add() method
     */
    public function setOptions()
    {

    	if( $this->f3->get('SESSION.lvl') <= 2 )
    	{
	        if( $this->f3->exists('POST.setOptions') ) {
	        	$eid = $this->f3->get('POST.eventID');
	        	$objEvent = new Events($this->db);
	        	$event = $objEvent->load(array('eid=?', $eid));
	            $objEventOptions = new EventOptions($this->db);
	            $eventOptions = $objEventOptions->add();

    			// copier le fichier facture.xls depuis tmp/downloads/ dans tmp/attachments
    			// renommer en facture[eid].xls
    			// mettre à jour les données des cellules
    			// enregistrer
    			$bill = $this->f3->get('DOWNLOADS').'/facture.xls';
				$event_bill = $this->f3->get('ATTACH').'/facture'.$eid.'.xls';
				if (!copy($bill, $event_bill)) {
    				$msg = $this->T('template_creation_failed');
	            	$this->setMessage($msg);
				} else {
					$billxls = new BillsWriter($this->f3);
					$billxls->defautDataOnCreate($event_bill, $event, $eventOptions);
					$msg = $this->T('the_event').' <b>'.$event->nom.'</b> '.$this->T('has_been').' '.$this->T('created').' !';
	    			$this->setMessage($msg);
				}

	            $this->f3->reroute('/events');
	        } else {
	            $this->f3->set('view', 'event/form/create_options.htm');
	        }
	    }
	    else
	    {
	    	$this->f3->reroute('/events');
	    }
    }

    /**
     * Update event datas
     * check ACL
     * 		set event, eventoptions and viewEventOptions objects
     *		if there is a POST update
     *			edit event
     *			edit eventoptions
     * 			if file attached to form
     *				check filesize
     *					set storage path
     *					set file object and name
     *					store file object in class attribute
     *					validate file object mimetype
     */
    public function update()
    {
    	if( $this->f3->get('SESSION.lvl') <= 2 )
    	{
	        $ev = new Events($this->db);
	        $evo = new EventOptions($this->db);
	        $eventOptions = new viewEventOptions($this->db);
	        $msg = '';
	        if($this->f3->exists('POST.update'))
	        {
	        	// $files = $web->receive(function($file,$formFieldName){
	            $ev->edit($this->f3->get('POST.eid'));
	            $evo->edit($this->f3->get('POST.eoid'));

	        	if($this->f3->get('FILES'))
	        	{
	        		$filesize = $this->f3->get('FILES');
	        		if($filesize['xls']['size']>0)
	        		{
	        			$storage = new \Upload\Storage\FileSystem($this->f3->get('ATTACH'));
	        			$file = new \Upload\File('xls', $storage);
	        			$file->setName('facture'.time().'.xls');
	        			$this->file = $file;
	        			$file->addValidations(array(
	        			    new \Upload\Validation\Mimetype(array('application/vnd.ms-excel','text/plain'))
	        			));

	        			try
	        			{
	        			    $file_uploaded = $file->upload();
	        			    if(!$file_uploaded)
	        			    {
	        			    	$this->errors('<b>'.$this->T('upload_aborted').'</b>, '.$this->T('template_not_replaced'));
	        			    }
	        			    else
	        			    {
	        			    	rename($this->f3->get('ATTACH').'/facture'.$ev->eid.'.xls', $this->f3->get('ATTACH').'/facture'.$ev->eid.'_'.time().'.xls');
	        			    	rename($this->f3->get('ATTACH').'/'.$this->file->getNameWithExtension(), $this->f3->get('ATTACH').'/facture'.$ev->eid.'.xls');
	        		    		$msg = '<br><span class="msg-spacer">&nbsp;</span>'.$this->T('upload_success').', '.$this->T('template_replaced').'. ';
	        			    }
	        			}
	        			catch (\Exception $e)
	        			{
	        				foreach($file->getErrors() as $er)
	        				{
	        					if(is_array($er))
	        					{
	        						foreach($er as $err)
	        						{
	        							$err = stristr($err, 'mimetype') ? $this->T('invalid_xls') : $err;
	        			    			$this->errors($err);
	        						}
	        					}
	        					else
	        					{
	        						$er = stristr($er, 'mimetype') ? $this->T('invalid_xls') : $er;
	        			    		$this->errors($er);
	        					}
	        				}
	        			    $this->errors('<b>'.$this->T('upload_aborted').'</b>, '.$this->T('template_not_replaced'));
	        			}
	        		}
	        	}

	            $msg = $this->T('the_event').' <b>'.$ev->nom.'</b> '.$this->T('has_been').' '.$this->T('updated') . $msg;
	            $this->setMessage($msg);
	            $this->f3->reroute('/events');
	        }
	        else
	        {
	          	$event_options = $eventOptions->getEventOptionsByEid($this->f3->get('PARAMS.eid'));
	          	$isold = $event_options->limitB > date('Y-m-d H:i:s');
	            $this->f3->mset(
	            	array(
	            		'event' => $event_options,
	            		'isold' => $isold,
	            		'view' => 'event/form/update.htm'
	            	)
	            );
	        }
	    }
	    else
	    {
	    	$this->f3->reroute('/events');
	    }
    }

    /**
     * Deactivate an event
     * That means only superAdmin and admin can access the event
     * All other credentials can just see it in the list of events
     */
    public function deactivate()
    {

    	if( $this->f3->get('SESSION.lvl') <= 2 )
    	{
	    	$event = new Events($this->db);
	    	if($this->f3->exists('POST.dea')) {
	    		$event->edit($this->f3->get('POST.eid'));
	    	}
    	}
    	$this->f3->reroute('/events');
    }

    /**
     * Activate an event
     */
    public function activate()
    {

    	if( $this->f3->get('SESSION.lvl') <= 2 )
    	{
	    	$event = new Events($this->db);
	    	if($this->f3->exists('POST.act')) {
	    		$event->edit($this->f3->get('POST.eid'));
	    	}
    	}
    	$this->f3->reroute('/events');
    }

	/**
	 * Delete an event
	 */
    public function delete()
    {

    	if( $this->f3->get('SESSION.lvl') <= 2 )
    	{
	    	$objEvent = new Events($this->db);
	    	if($this->f3->exists('POST.del')) {
	    		$eid = $this->f3->get('POST.eid');
	    		$event = $objEvent->load(array('eid=?',$eid));
	    		if($objEvent->delete($eid))
	    		{
	    			unlink($this->f3->get('ATTACH').'/facture'.$eid.'.xls');
	    			$msg = $this->T('the_event').' <b>'.$event->nom.'</b> '.$this->T('has_been_deleted');
	    			$this->setMessage($msg);
	    		}
	    		else
	    		{
	    			$msg = $this->T('the_event').' <b>'.$event->nom.'</b> '.$this->T('not_deleted');
	    			$this->setMessage($msg);
	    		}
	    	}
    	}
    	$this->f3->reroute('/events');
    }


}

