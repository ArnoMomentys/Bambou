<?php

/**
 * User controller class
 */
class UserController extends AuthController {


	/**
	 * Retrieve users records
	 * depending on params 'nom', 'societe' in the mysql view
	 *
	 * Get url params - l32
	 * Check if nom | societe | none - l33, l37, l41
	 * Set variables filters and options for the request - l34, l35
	 * Map the object to the mysql view - l46
	 * Perform the request with filters and options
	 * Set the variable lists (subset of results)
	 * Set the variable lysttype
	 * Set the variable listindex for eventual update or delete links
	 * Set the variable page_header for the page title
	 * Set the html view
	 * Instanciate the pager object
	 * Set the prefixes for paginated urls
	 * Set the pager template
	 * Set the variable pagebrowser with the pager template result
	 *
	 * @return array Paginated list
	 */
	public function allUsers()
	{
		if( $this->f3->get('SESSION.lvl') <= 2 )
		{
			$params = (object) array_map('trim', $this->f3->get('PARAMS'));
			$filter = (isset($params->filter) ? $params->filter : '');
			$filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');

			$filters = $options = [];
			$filterQuery = '1 = ?';
			$filterValue = null;

			if(empty($params->filter) && empty($params->optionkey))
			{
				$options['order'] = 'nom ASC';
			}
			else
			{
				if(empty($params->filter) && !empty($params->optionkey)) $params_filter = $params->optionkey;
				if(!empty($params->filter)) $params_filter = $params->filter;

				if(empty($params->option))
				{
				    $options['order'] = $params_filter.' ASC';
				}
				else
				{
				    if(empty($params->optionkey))
				    {
				        $options['order'] = $params_filter.' '.$params->optionvalue;
				    }
				    else
				    {
				        $options['order'] = $params->optionkey.' '.$params->optionvalue;
				    }
				}

				if(!empty($params->filtervalue))
				{
				    $filterQuery .= ' AND '.$params_filter.' LIKE ?';
				    $filterValue .= '%'.$params->filtervalue.'%';
				}
			}

			$filters[] = $filterQuery." AND nom != '' AND uid!=1";
			$filters[] = '1';

			if(!empty($filterValue)) $filters[] = $filterValue;

			$profiles = new viewUserCompleteProfile($this->db);
			$user_profiles = $profiles->getUsersProfilesFiltered_Paginated($filters, $options);

			$this->f3->mset(
				array(
					'lists_keys' => (isset($user_profiles['subset'][0]) ? array_keys($user_profiles['subset'][0]) : null),
					'lists' => $user_profiles['subset'],
					'totaux' => (isset($user_profiles['total']) && $user_profiles['total']>0 ? $user_profiles['total'] : 0),
					'listtype' => 'users',
					'listindex' => 'uid',
					'page_header' => $this->T('user_list'),
					'listname' => $this->T('users'),
					'filter' => $filter,
					'filtervalue' => $filtervalue,
					'search_fields' => $this->_getSearchFieldsParam($filtervalue),
					'search_uri_pattern' => preg_split('/\/[a-z]{1,}\/order/', $this->f3->get('PARAMS')[0]),
					'view' => 'user/list.htm'
				)
			);
			$pages = new Pagination($user_profiles['total'], $user_profiles['limit']);
			$pages->setRouteKeyPrefix('page/');
			$pages->setTemplate('pagination.htm');
			$this->f3->set('pagebrowser', $pages->serve());
		}
		else
		{
			$this->f3->reroute('/events');
		}
	}


	/**
	 * show a user profile
	 * only if :
	 * 		. I am s.admin
	 * 		. I am on my profile
	 * 		. the user is in my contact list
	 */
	public function profile()
	{
		$uid = $this->f3->get( 'PARAMS.uid' );
		$contact = new UserContacts( $this->db );
		$incontact = $contact->getHostIDContactID( $this->f3->get('SESSION.uid'), $this->f3->get('PARAMS.uid') );

		if( $this->f3->get('SESSION.lvl') <= 2 || $this->f3->get('SESSION.uid') == $this->f3->get('PARAMS.uid') || !empty($incontact) )
		{
			$usercomplete = new viewUserCompleteProfile($this->db);
			$user = $usercomplete->getUserFullProfileByUid($uid);

			if( is_object($user) ) {
				$usergroups = new viewUserGroups($this->db);
				$groups = $usergroups->getGroupsByUserID($uid);
				$creator = $usercomplete->getUserFullProfileByUid($user->creatorUid);
				$updator = !empty($user->updatedBy) ? $usercomplete->getUserFullProfileByUid($user->updatedBy) : null;
				$this->f3->mset(
					array(
						'user' => $user,
						'groups' => $groups,
						'creator' => $creator,
						'updator' => $updator,
						'page_header' => $this->T('user_profile').' : '.strtoupper($user->nom).' '.ucfirst($user->prenom),
						'view' => 'user/show.htm'
					)
				);
			} else {
				$this->f3->set('SESSION.warnings', $this->T('user_wrong_datas_contact_admin'));
				$this->f3->reroute('/users/list');
			}
		}
		else
		{
			$this->f3->reroute('/events');
		}
	}


	/**
	 *
	 */
	public function switchTo()
	{
		$uid = $this->f3->get('POST.u');
		if( $this->f3->get('SESSION.lvl') == 1 && $this->f3->get('SESSION.uid') != $uid )
		{
			$profile = new viewUserCompleteProfile($this->db);
			$user = $profile->getUserFullProfileByUid($uid);

			$aevents = [];
    		if($user->level >= 2) {
    			$events = new viewEventHostsInfos($this->db);
    			$myevents = $events->getHostEvents($uid);
    			foreach($myevents as $events) {
    				$aevents[] = $events->fields['eventid']['value'];
    			}
    		}
			$sevents = json_encode($aevents);
			$credentials = array(
				1 => 'Administrateur',
				2 => 'Administrateur',
				3 => 'Contact GDF SUEZ',
				4 => 'Accompagnant',
				5 => 'Représentant'
			);
			$creds = '[&nbsp;'.$credentials[$user->level].'&nbsp;]';

			$this->f3->set('SESSION.switch', Encrypt::load()->proceed(json_encode($this->f3->get('SESSION'), JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE)));

	    	$this->f3->mset(
	    		array(
	    			'SESSION.name' => $user->hash,
	    			'SESSION.uid' => $user->uid,
	    			'SESSION.lvl' => $user->level,
	    			'SESSION.c' => $user->creatorUid,
	    			'SESSION.cred' => $creds,
	    			'SESSION.profile.nom' => $user->nom,
	    			'SESSION.profile.prenom' => $user->prenom,
	    			'SESSION.events' => $sevents
	    		)
	    	);
		}
		$this->f3->reroute('/');

	}


	/**
	 *
	 */
	public function switchBack()
	{
		if( $this->f3->get('SESSION.switch') !== true )
		{
			$back = json_decode(Encrypt::load()->invert($this->f3->get('SESSION.switch')));
	    	$this->f3->mset(
	    		array(
	    			'SESSION.name' => $back->name,
	    			'SESSION.uid' => $back->uid,
	    			'SESSION.lvl' => $back->lvl,
	    			'SESSION.c' => $back->c,
	    			'SESSION.cred' => $back->cred,
	    			'SESSION.profile.nom' => $back->profile->nom,
	    			'SESSION.profile.prenom' => $back->profile->prenom,
	    			'SESSION.events' => $back->events,
	    			'SESSION.switch' => true,
	    			'SESSION.crp' => $back->crp
	    		)
	    	);
		}
		$this->f3->reroute('/');
	}


    /**
     * Add a user to the database
     *
     * ACL first : check if user has level 1,2,3 to perform an update
     * check if the post has a 'create' element
     * 		prepare a user object mapped from users table
     *   	set extra datas for user
     *    	add the user to users table
     *     	redirect to homepage
     * if no post param
     * 		set the view to call
     *
     */
    public function create()
    {
    	if( $this->f3->get('SESSION.lvl') <= 3 )
    	{
	        if($this->f3->exists('POST.create'))
	        {
	        	$post = array_map('trim', $this->f3->get('POST'));
	            $check = new Test(1);
	            $errors = array();
	            foreach($post as $post_index => $post_value)
	            {
	            	if(in_array($post_index, array('civilite','nom','prenom','email','societe')))
	            	{
	            		$t_text = 'user_'.$post_index.'_required';
		            	$check->expect(strlen($post_value)===0, $t_text);
		            }
		            if($post_index=='email')
		            {
		            	$t_email = 'user_'.$post_index.'_format';
		            	$check->expect(filter_var($post_value, FILTER_VALIDATE_EMAIL), $t_email);
		            }
		            if($post_index=='level')
		            {
		            	$t_level = 'user_'.$post_index.'_format';
		            	$check->expect(is_numeric($post_value), $t_level);
		            	$check->expect(($post_value>=1 && $post_value<=5), $t_level);
		            }
	            }
	            $r = $check->results();
	            if( empty($r) )
	            {
	            	$user = new Users($this->db);
		            $user->creatorUid = $this->f3->get('SESSION.uid');
		            $user->add();
		            $this->f3->reroute('/users');
	            }
	            else
	            {
	            	while (list(, $value) = each($r))
	            	{
	            		list($e, $i, $x) = explode('_', $value['text']);
	            		$this->errors($this->T($value['text']));
	            	}
	            	$this->f3->set('post_has_data', $post);
					$this->f3->set('view', 'user/form/create.htm');
	            }
	        }
	        else
	        {
		    	$this->f3->set('post_has_data', '');
				$this->f3->set('view', 'user/form/create.htm');
	        }
	    }
	    else
	    {
	    	$this->f3->reroute('/');
	    }
    }


    /**
     * complete a user profile
     */
    public function updateProfile()
    {
    	if( $this->f3->get('SESSION.lvl') <= 3 )
    	{
    		$uid = $this->f3->get('POST.uid') ? $this->f3->get('POST.uid') : $this->f3->get('PARAMS.uid');
    		$user = new Users($this->db);
    		$account = $user->load(array('uid=?', $uid));
    		$userprofile = new UserProfile($this->db);
    		$profile = $userprofile->load(array('userID=?', $uid));
    		$userjob = new UserJobinfos($this->db);
    		$job = $userjob->load(array('userID=?', $uid));
    		$userbilling = new UserBillinginfos($this->db);
    		$billing = $userbilling->load(array('userID=?', $uid));

    		if( $this->f3->exists('POST.complete-profile') )
    		{
 				$post = array_map('trim', $this->f3->get('POST'));
				$check = new Test(1);
				$errors = array();
				foreach($post as $post_index => $post_value) {
					if(in_array($post_index, array('civilite','nom','prenom','email','societe')))
	            	{
	            		$t_text = 'user_'.$post_index.'_required';
		            	$check->expect(strlen($post_value)===0, $t_text);
		            }
					if($post_index=='email') {
						$t_email = 'user_'.$post_index.'_format';
						$check->expect(filter_var($post_value, FILTER_VALIDATE_EMAIL)===false, $t_email);
					}
					if($post_index=='level') {
						$t_level = 'user_'.$post_index.'_format';
						$check->expect(is_numeric($post_value)===false, $t_level);
						$check->expect(($post_value>=3 && $post_value<=5)===false, $t_level);
					}
				}
				$r = $check->results();
				if(empty($r))
				{

					// save modified account infos
					$account->email = strtolower($post['email']);
					if(strlen($post['password'])>0) $account->password = Encrypt::load()->proceed($post['password']);
					if($this->f3->get('SESSION.lvl')==1 && $account->level !== 1) {
						$account->level = $post['level'];
					}
					$account->updatedAt = date('Y-m-d H:i:s');
					$account->updatedBy = $this->f3->get('SESSION.uid');
	            	$account->save();
	            	// save modified profile infos
	            	if(strlen($post['civilite'])>0) $profile->civilite = $post['civilite'];
	            	if(strlen($post['nom'])>0) $profile->nom = $post['nom'];
	            	if(strlen($post['prenom'])>0) $profile->prenom = $post['prenom'];
	            	$profile->save();
	            	// save modified job infos
	            	//$job->interlocuteurNom = $post['interlocuteurNom'];
	            	//$job->interlocuteurPrenom = $post['interlocuteurPrenom'];
	            	$job->fonction = $post['fonction'];
	            	$job->branche = $post['branche'];
	            	$job->societe = $post['societe'];
	            	$job->fixe = $post['fixe'];
	            	$job->portable = $post['portable'];
	            	$job->adresse = $post['adresse'];
	            	$job->cp = $post['cp'];
	            	$job->ville = $post['ville'];
	            	$job->pays = $post['pays'];
	            	$job->save();
	            	// save modified billing infos
	            	if(isset($post['b_siret']) || isset($post['b_tva']) || isset($post['b_organisme']) || isset($post['b_adresse']) || isset($post['b_cp']) || isset($post['b_ville']) || isset($post['b_pays']) || isset($post['b_imputation']) || isset($post['b_smart']) )
	            	{
	            		if(empty($billing)) $billing = $userbilling;
	            		$billing->userID = $uid;
		            	$billing->siret = $post['b_siret'];
						$billing->numTva = $post['b_tva'];
						$billing->organisme = $post['b_organisme'];
						$billing->adresse = $post['b_adresse'];
						$billing->cp = $post['b_cp'];
						$billing->ville = $post['b_ville'];
						$billing->pays = $post['b_pays'];
						$billing->imputation = $post['b_imputation'];
						$billing->smart =$post['b_smart'];
						$billing->save();
	            	}
	            	// update message
    				$this->f3->set('ESCAPE', FALSE);
	            	$msg = ucfirst($this->T('user_profile')).' '.$this->T('updated').' '.$this->T('for_user').' <b>'.strtoupper($profile->nom).' '.ucfirst($profile->prenom) . '</b>';
	            	$this->setMessage($msg);
	            	// format the url query string
	           		$refer = '/'.implode('/',explode('_',$this->f3->get('PARAMS.ref')));
	            	// redirect to the right page
            		$this->f3->reroute($refer);
	            }
	            else
	            {
	            	while (list(, $value) = each($r)) {
	            		list($e, $i, $x) = explode('_', $value['text']);
	            		$errors[$i][] = $this->T($value['text']);
	            	}
		           	$this->f3->mset(
		           		array(
		               		'account' => $account,
		               		'profile' => $profile,
		               		'job' => $job,
		               		'billing' => $billing,
		               		'ref' => $this->f3->get('PARAMS.ref'),
		               		'view' => 'user/form/publicprofile.htm'
		               	)
	               );
	           	}
    		}
    		else
    		{
				$this->f3->mset(
					array(
			    		'account' => $account,
			    		'profile' => $profile,
			    		'job' => $job,
			    		'billing' => $billing,
			    		'ref' => $this->f3->get('PARAMS.ref'),
			    		'view' => 'user/form/publicprofile.htm'
			    	)
			    );
    		}
    	}
    }


    /**
     * Delete a user from database
	 *
	 * ACL (access control list - check credentials before allowing the use of a service)
	 * check GET's uid param
     * prepare a user object mapped from users table
     * perform a delete action on the record
     * redirect to homepage
	 *
     * @return [type] [description]
     */
    public function delete()
    {
    	if( $this->f3->get('SESSION.lvl') <= 2 )
    	{
	        if($this->f3->exists('PARAMS.uid')) {
	            $user = new Users($this->db);
	            $user->delete($this->f3->get('PARAMS.uid'));
	        }
	        $this->f3->reroute('/users');
	    }
	    else
	    {
	    	$this->f3->reroute('/events');
	    }
    }


    /**
     * reroute the user to his right level url
     */
    public function hub()
    {
    	switch ($this->f3->get('SESSION.lvl')) {

    		// get the user to his stats page (admin level)
    		case '1':
    		case '2':
    			$cu = $this->db->exec('SELECT COUNT(`uid`) AS nb FROM users');
    			$gtu = $this->db->exec('SELECT COUNT(DISTINCT(`userID`)) AS nb FROM groupusers');
    			$ctg = $this->db->exec('SELECT COUNT(`gid`) AS nb FROM groups');
    			// profiles
    			$userscomplete = new viewUserCompleteProfile($this->db);
    			$last_logged_user = $userscomplete->getLastLoggedUserFullProfile();
				$last_logged_user_uid = '';
				$last_logged_user_nom_prenom = '';
    			if(is_object($last_logged_user)) {
    				$last_logged_user_uid = $last_logged_user->uid;
    				$last_logged_user_nom_prenom = $last_logged_user->nomcomplet;
    			}

    			// events
    			$events = new Events($this->db);
    			$cte = $this->db->exec('SELECT COUNT(`eid`) AS nb FROM events');
    			$cae = $this->db->exec('SELECT COUNT(`eid`) AS nb FROM events WHERE status=1');
    			$care = $this->db->exec('SELECT COUNT(`eid`) AS nb FROM events WHERE status=3');
    			$last_read_event = $events->getLastRead();
    			$last_read_event_eid = $last_read_event[0]['eid'];
    			$last_read_event_nom = $last_read_event[0]['nom'];

    			// stats
    			$eventOptions = new viewEventOptions($this->db);
    			$last_active_event = $eventOptions->getEventOptionsByStatusLessThan(3);
    			$stats_last_event = new viewEventStatsGuests($this->db);
				$stats_event = $stats_last_event->show($last_read_event_eid);

				if( count($stats_event) == 0 )
				{
					$stats_event=array();
					$stats_event[0] = new stdClass;

					$stats_event[0] = (object) MyMapper::getEmptyStatsArray($last_active_event->eid);
				}

    			// guest
    			$last_guest_added_event_eid = '';
    			$last_guest_added_event_name = '';
    			$last_guest_added_uid = '';
    			$last_guest_added_nom_prenom = '';
    			$invitations = new Invitations($this->db);
    			$invitation = $invitations->load('', array('order'=>'validatedAt DESC', 'limit'=>1));

    			if(!empty($invitation)) {
	    			$last_guest_added_event_eid = $invitation->eventID;
    				$invitation_event = $events->load(array('eid=?', $invitation->eventID));
    				$last_guest_added_event_nom = $invitation_event->nom;
    				$last_guest_added_uid = $invitation->guestID;
    				$invitation_guest = $userscomplete->getUserFullProfileByUid($invitation->guestID);
					if ($invitation_guest)
					{
						$last_guest_added_nom_prenom = $invitation_guest->nomcomplet.'&nbsp;&raquo;&nbsp;';
					}
    			}

    			// users
    			$last_user_added_uid = '';
    			$last_user_added_nom_prenom = '';
    			$last_user_added = $userscomplete->getLastCreatedUserFullProfile();
    			if(!empty($last_user_added)) {
	    			$last_user_added_uid = $last_user_added->uid;
	    			$last_user_added_nom_prenom = $last_user_added->nomcomplet;
    			}

    			// set variables
				$this->f3->mset(
					array(
						'page_header' => $this->T('homepage'),
						'event' => $last_active_event,
						'date' => date('Y-m-d'),
						'stats_last_active_event' => $stats_event,
						'count_total_events' => $cte[0]['nb'],
						'count_active_events' => $cae[0]['nb'],
						'count_archived_events' => $care[0]['nb'],
						'count_total_groups' => $ctg[0]['nb'],
						'count_total_groups_users' => $gtu[0]['nb'],
						'count_total_out_of_groups_users' => $cu[0]['nb'] - $gtu[0]['nb'],
						'last_logged_user_uid' => $last_logged_user_uid,
						'last_logged_user_nom_prenom' => $last_logged_user_nom_prenom,
						'last_read_event_eid' => $last_read_event_eid,
						'last_read_event_nom' => $last_read_event_nom,
						'last_guest_added_event_eid' => $last_guest_added_event_eid,
						'last_guest_added_event_nom' => $last_guest_added_event_name,
						'last_guest_added_uid' => $last_guest_added_uid,
						'last_guest_added_nom_prenom' => $last_guest_added_nom_prenom,
						'last_user_added_uid' => $last_user_added_uid,
						'last_user_added_nom_prenom' => $last_user_added_nom_prenom,
						'view' => 'user/board.htm'
					)
				);
    			break;

    		// get the user to his events page (host level)
    		case '3':
    			$this->f3->reroute('/events');
    			break;

    		// force logout
    		default:
    			$e = new Connexion();
    			$e->logOut();
    			break;
    	}
    }

    private function _getSearchFieldsParam($filtervalue)
    {
        $params = (object) array_map('trim', $this->f3->get('PARAMS'));
        $filter = isset($params->filter) ? $params->filter : '';
        return array(
            array(
                'filtervalue' => ($filter=='nomcomplet' ? $filtervalue : ''),
                'search_header' => $this->T('search_contact'),
                'search_pat' => "/users/list/search/nomcomplet/___/".(isset($params->optionkey)?$params->optionkey."/":"nomcomplet/")."order/asc",
                'no_search_pat' => "/users/list/"
            )
        );

    }
}

