<?php

/**
 * Event hosts controller class
 */
class EventHostsController extends AuthController {

    /**
     * allHosts
     * retrieve all hosts in an event
     * ACL
     * set first query filter with eventid
     * set options (order by ...)
     * add params to query filter if needed
     * concat all filters in an array like ( array('username = ? and password = ? and deleted = 0','John','acbd18db4cc2f85cedef654fccc4a4d8') )
     * instantiate a viewEventHostsInfos object
     * pass filters and options to method getHostsByEventIdFiltered_Paginated
     * set page params, pager params and view params
     * instantiate a pager and set the template
     */
    public function allHosts()
    {
        if( $this->f3->get('SESSION.lvl') <= 2 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $filter = (isset($params->filter) ? $params->filter : '');
            $filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');

            list($filters, $options) = $this->setFiltersAndOptions($params, array(null, null));

            // update event as read
            $e = new Events($this->db);
            $read = $e->setLastRead($params->eid);

            $eventOptions = new viewEventOptions($this->db);
            $event_options = $eventOptions->getEventOptionsByEid($params->eid);
            // get event's host infos
            $eventHosts = new viewEventHostsInfos( $this->db );
            $list = $eventHosts->getHostsByEventIdFiltered_Paginated($filters, $options);
            $hostStats = $eventHosts->getHostsInvitationsByEventid($params->eid);

            $isold = ($event_options->limitB < date('Y-m-d'));
            $this->f3->mset(
                array(
                    'lists_keys' => (isset($list['subset'][0]) ? array_keys($list['subset'][0]) : null),
                    'lists' => $list['subset'],
                    'listtype' => 'host',
                    'listname' => $list['total']>1 ? $this->T('event_hosts') : $this->T('event_host'),
                    'liststats' => $hostStats,
                    'event' => $event_options,
                    'isold' => $isold,
                    'totaux' => (isset($list['total']) && $list['total']>0 ? $list['total'] : 0),
                    'filter' => $filter,
                    'search_fields' => $this->_getSearchFieldsParam($filtervalue),
                    'search_uri_pattern' => preg_split('/\/[a-z]{1,}\/order/', $this->f3->get('PARAMS')[0]),
                    'view' => 'event/listhosts.htm'
                )
            );
            $pages = new Pagination($list['total'], $list['limit']);
            $pages->setRouteKeyPrefix('page/');
            $pages->setTemplate('pagination.htm');
            $this->f3->set('pagebrowser', $pages->serve());
        }
        else
        {
            $this->f3->reroute('/');
        }
    }

    /**
     * get all
     * @return [type] [description]
     */
    public function allNotHost()
    {
        if( $this->f3->get('SESSION.lvl') <= 2 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));

            $filter = (isset($params->filter) ? $params->filter : '');
            $filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');

            $eventHosts = new EventHosts($this->db);
            $count_event_hosts = $eventHosts->count(array('eventID=?', $params->eid));
            $aevents_hosts = [1];

            if($count_event_hosts > 0)
            {
                $event_hosts = $eventHosts->select('hostID', array('eventID=?', $params->eid));
                foreach($event_hosts as $event_host)
                {
                    $aevents_hosts[] = $event_host->hostID;
                }
            }

            $filters = $options = [];
            $filterValue = '';
            $filterQuery = 'uid '.($count_event_hosts > 0 ? 'NOT IN ('.implode(",", $aevents_hosts).')' : 'IS NOT NULL AND uid!=1');

            if(empty($params->filter))
            {
                $options['order'] = 'nom ASC';
            }
            else
            {
                $params_filter = $params->filter;
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

            if( !empty($filterValue) ) $filters[] = $filterValue;

            $profiles = new viewUserCompleteProfile($this->db);
            $hosts = $count_event_hosts > 0 ?
                $profiles->getUsersProfilesWithUidNOTInListFiltered_Paginated($filters, $options) :
                $profiles->getUsersProfilesFiltered_Paginated($filters, $options);

            $eventOptions = new viewEventOptions($this->db);
            $event_options = $eventOptions->getEventOptionsByEid($params->eid);

            $this->f3->mset(
                array(
                    'eid'                   => $params->eid,
                    'event'                 => $event_options,
                    'isold'                 => ($event_options->limitA < date('Y-m-d')),
                    'lists_keys'            => (isset($hosts['subset'][0]) ? array_keys($hosts['subset'][0]) : null),
                    'lists'                 => $hosts['subset'],
                    'totaux'                => (isset($hosts['total']) && $hosts['total']>0 ? $hosts['total'] : 0),
                    'listtype'              => 'events',
                    'listindex'             => 'eid',
                    'listname'              => $hosts['total']>1 ? $this->T('contacts') : $this->T('contact'),
                    'filter'                => $filter,
                    'filtervalue'           => $filtervalue,
                    'search_fields'         => $this->_getSearchFieldsParam($filtervalue, 'add'),
                    'search_uri_pattern'    => preg_split('/\/[a-z]{1,}\/order/', $this->f3->get('PARAMS')[0]),
                    'complete_profile'      => "event_".$params->eid."_add_host",
                    'view' => 'event/addhost.htm'
                )
            );
            $pages = new Pagination($hosts['total'], $hosts['limit']);
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
     * addHost
     *
     */
    public function addHost()
    {
        if( $this->f3->get('SESSION.lvl') <= 2 )
        {
            if($this->f3->exists('POST.addHost') || $this->f3->exists('PARAMS.addHost')) {
                $event_host = new EventHosts($this->db);
                $event_host->add();
                if($this->f3->get('POST'))
                {
                    $guest = new viewUserCompleteProfile($this->db);
                    $u = $guest->getUserFullProfileByUid_Raw($this->f3->get('POST.hostID'));
                    $msg = ucfirst($this->T('event_host')).' <b class="textracap">'.$u[0]['nom'].' '.$u[0]['prenom'].'</b> '.$this->T('added').' '.$this->T('to_event');
                }
                else
                {
                    $msg = '<b>'.ucfirst($this->T('event_host')).'</b> '.$this->T('added').' '.$this->T('to_event');
                }
                $this->setMessage($msg);
            }
        }
        $this->f3->reroute('/event/'.$this->f3->get('POST.eventID').'/show/hosts');
    }

    /**
     * add a newhost to an event
     */
    public function addNewHost()
    {
        if( $this->f3->get('SESSION.lvl') <= 3 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $creator = $this->f3->get('SESSION.uid');
            $event = new viewEventOptions($this->db);
            $e = $event->getEventOptionsByEid($params->eid);
            $msg = '';
            if($this->f3->exists('POST.reqprof'))
            {
                $post = array_map('trim', $this->f3->get('POST'));
                $check = new Test(1);
                $errors = [];
                foreach($post as $post_index => $post_value)
                {
                    if(in_array($post_index, array('civilite','nom','prenom','email','societe')))
                    {
                        $t_text = 'user_'.$post_index.'_required';
                        $check->expect(strlen($post_value)===0, $t_text);
                    }
                    if($post_index=='civilite')
                    {
                        $t_gender = 'user_'.$post_index.'_format';
                        $genders = ['mr', 'monsieur', 'mme', 'madame', 'mlle', 'mademoiselle', 'société', 'societe'];
                        $gender_minus = strtolower($post_value);
                        $check->expect(in_array($gender_minus, $genders)===false, $t_gender);
                    }
                    if($post_index=='email')
                    {
                        $t_email = 'user_'.$post_index.'_format';
                        $check->expect(filter_var($post_value, FILTER_VALIDATE_EMAIL)===false, $t_email);
                    }
                }
                $r = $check->results();
                if(empty($r))
                {
                    $user_host = new Users($this->db);
                    $user_profile = new UserProfile($this->db);
                    $user_job = new UserJobinfos($this->db);
                    $user_eventhost = new EventHosts($this->db);
                    $host_exists = $user_host->load(array('email=?', $post['email']));
                    if(empty($host_exists))
                    {
                        $host_exists = $user_host;
                        $host_exists->email = $post['email'];
                        $host_exists->password = Encrypt::load()->proceed($this->f3->get('db_pass'));
                        $host_exists->level = 3;
                        $host_exists->creatorUid = $creator;
                        $host_exists->save();
                        $host_exists->load(array('email=?', $post['email']));
                        $msg .= 'Compte de l\'Invitant créé, ';
                    }
                    $host_profile = $user_profile->load(array('userID=?', $host_exists->uid));
                    if(empty($host_profile))
                    {
                        $host_profile = $user_profile;
                        $host_profile->userID = $host_exists->uid;
                    }
                    $host_job = $user_job->load(array('userID=?', $host_exists->uid));
                    if(empty($host_job))
                    {
                        $host_job = $user_job;
                        $host_job->userID = $host_exists->uid;
                    }
                    $host_event = $user_eventhost->load(array('hostID=? AND eventID=?', $host_exists->uid, $params->eid));
                    if(empty($host_event))
                    {
                        $host_event = $user_eventhost;
                        $host_event->hostID = $host_exists->uid;
                        $host_event->eventID = $params->eid;
                        $host_event->save();
                        $msg .= 'Statut invitant créé, ';
                    }
                    $host_profile->civilite = ucfirst($post['civilite']);
                    $host_profile->nom = strtoupper($post['nom']);
                    $host_profile->prenom = ucfirst($post['prenom']);
                    $host_profile->save();
                    $msg2 = '<b>' . strtoupper($host_profile->nom).' '.ucfirst($host_profile->prenom).'</b> : Profil complété, ';
                    if(strlen($post['adresse'])>0) $host_job->adresse = $post['adresse'];
                    if(strlen($post['ville'])>0) $host_job->ville = $post['ville'];
                    if(strlen($post['cp'])>0) $host_job->cp = $post['cp'];
                    if(strlen($post['pays'])>0) $host_job->pays = $post['pays'];
                    if(strlen($post['portable'])>0) $host_job->portable = $post['portable'];
                    if(strlen($post['fixe'])>0) $host_job->fixe = $post['fixe'];
                    if(strlen($post['fonction'])>0) $host_job->fonction = $post['fonction'];
                    if(strlen($post['branche'])>0) $host_job->branche = $post['branche'];
                    if(strlen($post['BU'])>0) $host_job->BU = $post['BU'];
                    if(strlen($post['societe'])>0) $host_job->societe = $post['societe'];
                    $host_job->save();
                    $msg = $msg2 . 'Infos professionnels créés, ' . $msg;
                    $msg = $this->setMessage($msg);
                    // $this->f3->reroute('/event/'.$params->eid.'/show/hosts');
                    $this->f3->reroute('/user/'.$host_exists->uid.'/show');
                }
                else
                {
                    while (list(, $value) = each($r))
                    {
                        list($y, $i, $x) = explode('_', $value['text']);
                        $this->errors($this->T($value['text']));
                    }
                    $this->f3->mset(
                        array(
                            'event' => $e,
                            'post_has_data' => $post,
                            'view' => 'user/form/requiredprofilehost.htm'
                        )
                    );
                }
            } else {
                $this->f3->mset(
                    array(
                        'event' => $e,
                        'isold' => ($e->limitB < date('Y-m-d H:i:s') ? true : false),
                        'post_has_data' => '',
                        'view' => 'user/form/requiredprofilehost.htm'
                    )
                );
            }
        }
    }

    /**
     * removeHost
     */
    public function removeHost()
    {
        if( $this->f3->get('SESSION.lvl') <= 2 )
        {
            if($this->f3->exists('POST.delh'))
            {
                $host = new EventHosts($this->db);
                $host->delete(array($this->f3->get('POST.eventID'), $this->f3->get('POST.hostID')));
                $msg = ucfirst($this->T('event_host')).' <b class="textracap">'.$this->f3->get('POST.hostname').'</b> '.$this->T('removed').' '.$this->T('from_event');
                $this->setMessage($msg);
            }
        }
        $this->f3->reroute('/event/'.$this->f3->get('POST.eventID').'/show/hosts');
    }

    private function setFiltersAndOptions($params, $criteria)
    {
        $filters = $options = [];
        $filterQuery = 'eventid = ?';
        $filterValue = '';

        if(empty($params->filter) && empty($params->optionkey))
        {
            $options['order'] = 'hostname ASC';
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

        $filters[] = $filterQuery." AND hostname != ''".(!empty($criteria[1])?' AND '.$criteria[1]:'');
        $filters[] = $params->eid;

        if(!empty($filterValue)) $filters[] = $filterValue;
        if(!empty($criteria[0])) $filters[] = $criteria[0];

        return array($filters, $options);
    }

    private function _getSearchFieldsParam($filtervalue, $tplAction='')
    {
        $params = (object) array_map('trim', $this->f3->get('PARAMS'));
        $filter = isset($params->filter) ? $params->filter : '';
        if($tplAction=='add')
        {
            return array(
                        array(
                            'filtervalue' => ($filter=='nomcomplet' ? $filtervalue : ''),
                            'search_header' => $this->T('search_contact'),
                            'search_pat' => "/event/$params->eid/add/host/search/nomcomplet/___/".(isset($params->optionkey)?$params->optionkey."/":"nomcomplet/")."order/asc",
                            'no_search_pat' => "/event/$params->eid/add/host"
                        )
                    );
        }

        return array(
            array(
                'filtervalue' => ($filter=='hostname' ? $filtervalue : ''),
                'search_header' => $this->T('search_host'),
                'search_pat' => "/event/$params->eid/show/hosts/search/hostname/___/".(isset($params->optionkey)?$params->optionkey."/":"hostname/")."order/asc",
                'no_search_pat' => "/event/$params->eid/show/hosts"
            )
        );

    }
}

