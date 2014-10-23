<?php

/**
 * Event guests controller class
 */
class EventGuestsController extends AuthController {

    /**
     * allGuests
     */
    public function allGuests()
    {
        $aevents = json_decode($this->f3->get('SESSION.events'));
        // si je suis admin ou invitant
        if( ($this->f3->get('SESSION.lvl')==3 && in_array($this->f3->get('PARAMS.eid'), $aevents)) || $this->f3->get('SESSION.lvl')<=2 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $filter = (isset($params->filter) ? $params->filter : '');
            $filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');
            $filters = $options = [];
            $filterQuery = $this->f3->get('SESSION.lvl') <= 2 ? 'eid=?' : 'eid=? AND hostid=?';
            $filterValue = '';
            if( empty($params->filter) )
            {
                $options['order'] = 'guestname ASC';
            }
            else
            {
                $options['order'] = empty($params->option) ? $params->filter.' ASC' : $params->filter.' '.$params->optionvalue;
                if( !empty($params->filtervalue) )
                {
                    $filterQuery .= ' AND '.$params->filter.' LIKE ?';
                    $filterValue .= '%'.$params->filtervalue.'%';
                }
            }
            $filters[] = $filterQuery." AND guestname != ''";
            $filters[] = $params->eid;
            if( $this->f3->get('SESSION.lvl') > 2 )
            {
                $filters[] = $this->f3->get('SESSION.uid');
            }

            if( !empty($filterValue) )
            {
                $filters[] = $filterValue;
            }

            $event = new viewEventOptions($this->db);
            $e = $event->getEventOptionsByEid($params->eid);

            $eventGuests = new viewEventsEventGuests($this->db);
            $list = $eventGuests->getEventGuestsPaginated($filters, $options);
            $isEventOld = ($e->limitB < date('Y-m-d') ? true : false);
            $isEventDone = ($e->debut <= date('Y-m-d') ? true : false);

            $this->f3->mset(
                array(
                    'lists_keys' => (isset($list['subset'][0]) ? array_keys($list['subset'][0]) : null),
                    'lists' => $list['subset'],
                    'listtype' => 'guest',
                    'listindex' => 'eid',
                    'event' => $e,
                    'isold' => $isEventOld,
                    'isdone' => $isEventDone,
                    'totaux' => (isset($list['total']) && $list['total']>0 ? $list['total'] : 0),
                    'listname' => $list['total']>1 ? $this->T('event_guests') : $this->T('event_guest'),
                    'search_header' => $this->T('search_guest'),
                    'page_header' => ($this->f3->get('SESSION.lvl')<=2 ? ucfirst($this->T('all_guests_list')) : ucfirst($this->T('my_guests_list'))),
                    'filter' => $filter,
                    'filtervalue' => $filtervalue,
                    'search_pat' => "/event/$params->eid/show/guests/guestname/___/order/asc",
                    'no_search_pat' => "/event/$params->eid/show/guests",
                    'view' => 'event/listguests.htm'
                )
            );
            $pages = new Pagination($list['total'], $list['limit']);
            $pages->setRouteKeyPrefix('page/');
            $pages->setTemplate('pagination.htm');
            $this->f3->set('pagebrowser', $pages->serve());
        }
        // je n'ai pas accès à la liste des invités
        else
        {
            $this->f3->reroute('/events');
        }
    }

    /**
     * allGuestsAnswer
     * à améliorer car fait dans l'urgence
     */
    public function allGuestsAnswer()
    {
        $aevents = json_decode($this->f3->get('SESSION.events'));
        // si je suis admin ou invitant
        if( ($this->f3->get('SESSION.lvl')==3 && in_array($this->f3->get('PARAMS.eid'), $aevents)) || $this->f3->get('SESSION.lvl')<=2 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $rep = $params->reponse > 2 ? 0 : $params->reponse;
            $filter = (isset($params->filter) ? $params->filter : '');
            $filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');
            $filters = $options = [];
            $filterQuery = $this->f3->get('SESSION.lvl') <= 2 ? 'eid=?' : 'eid=? AND hostid=?';
            $filterValue = '';
            if( empty($params->filter) )
            {
                $options['order'] = 'guestname ASC';
            }
            else
            {
                $options['order'] = empty($params->option) ? $params->filter.' ASC' : $params->filter.' '.$params->optionvalue;
                if( !empty($params->filtervalue) )
                {
                    $filterQuery .= ' AND '.$params->filter.' LIKE ?';
                    $filterValue .= '%'.$params->filtervalue.'%';
                }
            }
            $filters[] = $filterQuery." AND guestname != '' AND answer = ".$rep;
            $filters[] = $params->eid;
            if( $this->f3->get('SESSION.lvl') > 2 )
            {
                $filters[] = $this->f3->get('SESSION.uid');
            }

            if( !empty($filterValue) )
            {
                $filters[] = $filterValue;
            }

            $event = new viewEventOptions($this->db);
            $e = $event->getEventOptionsByEid($params->eid);

            $eventGuests = new viewEventsEventGuests($this->db);
            $list = $eventGuests->getEventGuestsPaginated($filters, $options);
            $isEventOld = ($e->limitB < date('Y-m-d') ? true : false);
            $isEventDone = ($e->debut <= date('Y-m-d') ? true : false);

            if($this->f3->get('SESSION.lvl')<=2) {
                if($rep == 0) $page_header = ucfirst($this->T('all_guests_answer_noanswer'));
                if($rep == 1) $page_header = ucfirst($this->T('all_guests_answer_present'));
                if($rep == 2) $page_header = ucfirst($this->T('all_guests_answer_absent'));
            } else {
                if($rep == 0) $page_header = ucfirst($this->T('my_guests_answer_noanswer'));
                if($rep == 1) $page_header = ucfirst($this->T('my_guests_answer_present'));
                if($rep == 2) $page_header = ucfirst($this->T('my_guests_answer_absent'));
            }

            $this->f3->mset(
                array(
                    'lists_keys' => (isset($list['subset'][0]) ? array_keys($list['subset'][0]) : null),
                    'lists' => $list['subset'],
                    'listtype' => 'guest',
                    'listindex' => 'eid',
                    'event' => $e,
                    'isold' => $isEventOld,
                    'isdone' => $isEventDone,
                    'totaux' => (isset($list['total']) && $list['total']>0 ? $list['total'] : 0),
                    'listname' => $list['total']>1 ? $this->T('event_guests') : $this->T('event_guest'),
                    'search_header' => $this->T('search_guest'),
                    'page_header' => $page_header,
                    'filter' => $filter,
                    'filtervalue' => $filtervalue,
                    'search_pat' => "/event/$params->eid/show/guests/guestname/___/order/asc",
                    'no_search_pat' => "/event/$params->eid/show/guests",
                    'view' => 'event/listguests.htm'
                )
            );
            $pages = new Pagination($list['total'], $list['limit']);
            $pages->setRouteKeyPrefix('page/');
            $pages->setTemplate('pagination.htm');
            $this->f3->set('pagebrowser', $pages->serve());
        }
        // je n'ai pas accès à la liste des invités
        else
        {
            $this->f3->reroute('/events');
        }
    }

    /**
     * allGuestsPresence
     * à améliorer car fait dans l'urgence
     */
    public function allGuestsPresence()
    {
        $aevents = json_decode($this->f3->get('SESSION.events'));
        // si je suis admin ou invitant
        if( ($this->f3->get('SESSION.lvl')==3 && in_array($this->f3->get('PARAMS.eid'), $aevents)) || $this->f3->get('SESSION.lvl')<=2 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $pres = $params->presence > 2 ? 0 : $params->presence;
            $filter = (isset($params->filter) ? $params->filter : '');
            $filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');
            $filters = $options = [];
            $filterQuery = $this->f3->get('SESSION.lvl') <= 2 ? 'eid=?' : 'eid=? AND hostid=?';
            $filterValue = '';
            if( empty($params->filter) )
            {
                $options['order'] = 'guestname ASC';
            }
            else
            {
                $options['order'] = empty($params->option) ? $params->filter.' ASC' : $params->filter.' '.$params->optionvalue;
                if( !empty($params->filtervalue) )
                {
                    $filterQuery .= ' AND '.$params->filter.' LIKE ?';
                    $filterValue .= '%'.$params->filtervalue.'%';
                }
            }
            $filters[] = $filterQuery." AND guestname != ''  AND presence = ".($params->presence > 1 ? 0 : $params->presence);
            $filters[] = $params->eid;
            if( $this->f3->get('SESSION.lvl') > 2 )
            {
                $filters[] = $this->f3->get('SESSION.uid');
            }

            if( !empty($filterValue) )
            {
                $filters[] = $filterValue;
            }

            $event = new viewEventOptions($this->db);
            $e = $event->getEventOptionsByEid($params->eid);

            $eventGuests = new viewEventsEventGuests($this->db);
            $list = $eventGuests->getEventGuestsPaginated($filters, $options);
            $isEventOld = ($e->limitB < date('Y-m-d') ? true : false);
            $isEventDone = ($e->debut <= date('Y-m-d') ? true : false);

            if($this->f3->get('SESSION.lvl')<=2) {
                if($pres == 0) $page_header = ucfirst($this->T('all_guests_presence_absent'));
                if($pres == 1) $page_header = ucfirst($this->T('all_guests_presence_present'));
            } else {
                if($pres == 0) $page_header = ucfirst($this->T('my_guests_presence_absent'));
                if($pres == 1) $page_header = ucfirst($this->T('my_guests_presence_present'));
            }

            $this->f3->mset(
                array(
                    'lists_keys' => (isset($list['subset'][0]) ? array_keys($list['subset'][0]) : null),
                    'lists' => $list['subset'],
                    'listtype' => 'guest',
                    'listindex' => 'eid',
                    'event' => $e,
                    'isold' => $isEventOld,
                    'isdone' => $isEventDone,
                    'totaux' => (isset($list['total']) && $list['total']>0 ? $list['total'] : 0),
                    'listname' => $list['total']>1 ? $this->T('event_guests') : $this->T('event_guest'),
                    'search_header' => $this->T('search_guest'),
                    'filter' => $filter,
                    'filtervalue' => $filtervalue,
                    'search_pat' => "/event/$params->eid/show/guests/guestname/___/order/asc",
                    'no_search_pat' => "/event/$params->eid/show/guests",
                    'view' => 'event/listguests.htm'
                )
            );
            $pages = new Pagination($list['total'], $list['limit']);
            $pages->setRouteKeyPrefix('page/');
            $pages->setTemplate('pagination.htm');
            $this->f3->set('pagebrowser', $pages->serve());
        }
        // je n'ai pas accès à la liste des invités
        else
        {
            $this->f3->reroute('/events');
        }
    }


    /**
     * Tous mes contacts
     * qui ne sont encore dans ma liste des invités à cet évènement
     */
    public function allNotGuest()
    {

        $aevents = json_decode($this->f3->get('SESSION.events'));
        // si je suis admin ou invitant
        if( (in_array($this->f3->get('SESSION.lvl'), array(2,3)) && in_array($this->f3->get('PARAMS.eid'), $aevents)) || $this->f3->get('SESSION.lvl')==1 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $filter = (isset($params->filter) ? $params->filter : '');
            $filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');
            $event = new viewEventOptions($this->db);
            $event_options = $event->getEventOptionsByEid($params->eid);
            // pour un admin
            // il ne faut pas qu'il puisse inviter des gens qu'il a déjà ajouté en tant qu'invitant
            if($this->f3->get('SESSION.lvl')==1)
            {
                $contacts = new viewUserCompleteProfile($this->db);
                $contacts = $contacts->getUsersUids_Raw();
                $contacts_ids = [];
                foreach($contacts as $contact)
                {
                    $contacts_ids[] = $contact['uid'];
                }

                $eventGuests = new viewEventHostsInfos($this->db);
                $busy_ids = $eventGuests->getHostsIdsByEventId($params->eid); // hosts
            }
            // pour un invitant
            // il ne doit pas pouvoir inviter des personnes qu'il a déjà invité à un même evenement
            else
            {
                $contacts = new UserContacts($this->db);
                $contacts = $contacts->getHostContactsIds($this->f3->get('SESSION.uid'));
                $contacts_ids = [];
                foreach($contacts as $contact)
                {
                    $contacts_ids[] = $contact->contactID;
                }
                $eventGuests = new viewEventsEventGuests($this->db);
                $busy_ids = $eventGuests->getGuestsByHost($this->f3->get('SESSION.uid'), $params->eid); // guests
            }
            $invitable_contacts = array_diff($contacts_ids, $busy_ids);
            $filters = $options = [];
            $filterValue = '';
            $filterQuery = count($invitable_contacts) > 1 ? 'uid IN ('.implode(',', $invitable_contacts).')' : 'uid='.$this->f3->get('SESSION.uid');

            if( empty($params->filter) )
            {
                $options['order'] = 'nom ASC';
            }
            else
            {
                $options['order'] = empty($params->option) ? $params->filter.' ASC' : $params->filter.' '.$params->optionvalue;
                if( !empty($params->filtervalue) )
                {
                    $filterQuery .= ' AND '.$params->filter.' LIKE ?';
                    $filterValue .= '%'.$params->filtervalue.'%';
                }
            }
            $filters[] = $filterQuery." AND nom != '' AND uid!=1";
            if( !empty($filterValue) )
            {
                $filters[] = $filterValue;
            }
            $profiles = new viewUserCompleteProfile($this->db);
            $list = $profiles->getUsersProfilesWithUidInListFiltered_Paginated($filters, $options);
            $this->f3->mset(
                array(
                    'lists_keys' => (isset($list['subset'][0]) ? array_keys($list['subset'][0]) : null),
                    'lists' => $list['subset'],
                    'event' => $event_options,
                    'totaux' => (isset($list['total']) && $list['total']>0 ? $list['total'] : 0),
                    'isold' => false,
                    'page_header' => ucfirst($this->T('event_list')).' : '.ucfirst($event_options->nom),
                    'listname' =>$list['total']>1 ? $this->T('contacts') : $this->T('contact'),
                    'filter' => $filter,
                    'filtervalue' => $filtervalue,
                    'search_header' => $this->T('search_user_to_invite'),
                    'search_pat' => "/event/$params->eid/add/guest/nom/___/order/asc",
                    'no_search_pat' => "/event/$params->eid/add/guest",
                    'complete_profile' => "event_".$params->eid."_add_guest",
                    'view' => 'event/addguest.htm'
                )
            );
            $pages = new Pagination($list['total'], $list['limit']);
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
     * add a guest to an event from POST modal form
     */
    public function addGuest()
    {
        // créer une invitation
        // créer une entrée invitationGuest
        $aevents = json_decode($this->f3->get('SESSION.events'));
        if( (in_array($this->f3->get('SESSION.lvl'), array(2,3)) && in_array($this->f3->get('PARAMS.eid'), $aevents)) || $this->f3->get('SESSION.lvl')==1 )
        {
            if( $this->f3->exists('POST.addGuest') || $this->f3->exists('PARAMS.addGuest') )
            {
                $invitation = new Invitations($this->db);
                $invitation->add();
                $invitation_guest = new InvitationGuests($this->db);
                $invitation_guest->add($invitation->iid);
                if($this->f3->get('POST'))
                {
                    $guest = new viewUserCompleteProfile($this->db);
                    $u = $guest->getUserFullProfileByUid_Raw($this->f3->get('POST.guestID'));
                    $msg = ucfirst($this->T('invitation')).' '.$this->T('created_fem').', <b class="textracap">'.$u[0]['nom'].' '.$u[0]['prenom'].'</b> '.$this->T('invited').' '.$this->T('to_event');
                }
                else
                {
                    $msg = ucfirst($this->T('invitation')).' '.$this->T('created_fem').', '.ucfirst($this->T('event_guest')).' '.$this->T('added').' '.$this->T('to_event');
                }
                $this->setMessage($msg);
            }
        }
        $this->f3->reroute('/event/'.$this->f3->get('POST.eventID').'/show/guests');
    }

    /**
     * add a new guest to an event
     * /event/@eid/add/new/guest
     */
    public function addNewGuest()
    {
        $aevents = json_decode($this->f3->get('SESSION.events'));
        if( (in_array($this->f3->get('SESSION.lvl'), array(2,3)) && in_array($this->f3->get('PARAMS.eid'), $aevents)) || $this->f3->get('SESSION.lvl')==1 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $hostid = $this->f3->get('SESSION.uid');
            $event = new viewEventOptions($this->db);
            $e = $event->getEventOptionsByEid($params->eid);
            $msg = '';
            if($this->f3->exists('POST.reqprof'))
            {
                $post = array_map('trim', $this->f3->get('POST'));
                $check = new Test(1);
                $requiredArray = array('civilite','nom','prenom','societe');
                if ($this->f3->get('SESSION.lvl') <= 2)
                {
                	$requiredArray[] = 'email';
                }
                
                foreach($post as $post_index => $post_value)
                {
                    if(in_array($post_index, array('accgender','accnom','accprenom','accemail','reprgender','reprnom','reprprenom','repremail','reqprof')))
                        continue;
                    
                    if(in_array($post_index, $requiredArray))
                    {
                        $t_text = 'user_'.$post_index.'_required';
                        $check->expect(strlen($post_value)===0, $t_text);
                    }
                    if(($post_index=='email' && array_search($post_index, $requiredArray) !== false) ||$post_index=='accemail'||$post_index=='repremail')
                    {
                        $t_email = 'user_'.$post_index.'_format';
                        $check->expect(filter_var($post_value, FILTER_VALIDATE_EMAIL)===false, $t_email);
                    }
                }
                $r = $check->results();
                if(empty($r))
                {
                    $user = new Users($this->db);
                    $profile = new UserProfile($this->db);
                    $job = new UserJobinfos($this->db);
                    $contact = new UserContacts($this->db);
                    $invite = new Invitations($this->db);
                    $guest = new InvitationGuests($this->db);
                    $represent = new InvitationRepresentative($this->db);
                    $accompany = new InvitationAccompanying($this->db);

                    $user_exists = $user->load(array('email=?', $post['email']));
                    if(empty($user_exists))
                    {
                        $user_exists = $user;
                        $user_exists->email = $post['email'];
                        $user_exists->password = Encrypt::load()->proceed($this->f3->get('db_pass'));
                        $user_exists->level = 3;
                        $user_exists->creatorUid = $hostid;
                        $user_exists->createdAt = date('Y-m-d H:i:s');
                        $user_exists->save();
                        $user_exists->load(array('email=?', $post['email']));
                        $msg .= 'Compte de l\'Invité créé, ';
                    }
                    $user_profile = $profile->load(array('userID=?', $user_exists->uid));
                    if(empty($user_profile))
                    {
                        $user_profile = $profile;
                        $user_profile->userID = $user_exists->uid;
                    }
                    $user_job = $job->load(array('userID=?', $user_exists->uid));
                    if(empty($user_job))
                    {
                        $user_job = $job;
                        $user_job->userID = $user_exists->uid;
                    }
                    $user_contact = $contact->load(array('hostID=? AND contactID=?', $hostid, $user_exists->uid));
                    if(empty($user_contact))
                    {
                        $user_contact = $contact;
                        $user_contact->hostID = $hostid;
                        $user_contact->contactID = $user_exists->uid;
                        $user_contact->save();
                        $user_contact->load(array('hostID=? AND contactID=?', $hostid, $user_exists->uid));
                        $msg .= 'Contact créé, ';
                    }
                    $invitation = $invite->load(array('hostID=? AND guestID=? AND eventID=?', $hostid, $user_exists->uid, $params->eid));
                    if(empty($invitation))
                    {
                        $invitation = $invite;
                        $invitation->hostID = $hostid;
                        $invitation->guestID = $user_exists->uid;
                        $invitation->eventID = $params->eid;
                        $invitation->save();
                        $invitation->load(array('hostID=? AND guestID=? AND eventID=?', $hostid, $user_exists->uid, $params->eid));
                        $msg .= 'Invitation créée, ';
                    }
                    $invitation_guest = $guest->load(array('invitationID=?', $invitation->iid));
                    if(empty($invitation_guest))
                    {
                        $invitation_guest = $guest;
                        $invitation_guest->invitationID = $invitation->iid;
                        $invitation_guest->save();
                        $msg .= 'Invité créé, ';
                    }
                    if(!empty($post['repremail']))
                    {
                        $invitation_represent = $represent->load(array('invitationID=?', $invitation->iid));
                        if(empty($invitation_represent))
                        {
                            $repr = new Users($this->db);
                            $reprprofile = new UserProfile($this->db);
                            $rmail = $post['repremail'];
                            $user_repr = $repr->load(array('email=?', $rmail));
                            if(empty($user_repr))
                            {
                                $repr->email = $rmail;
                                $repr->password = Encrypt::load()->proceed($this->f3->get('db_pass'));
                                $repr->level = 3;
                                $repr->creatorUid = $hostid;
                                $repr->createdAt = date('Y-m-d H:i:s');
                                $repr->save();
                                $user_repr = $repr->load(array('email=?', $rmail));
                                $msg .= 'Compte du représentant créé, ';
                            }
                            $represent->invitationID = $invitation->iid;
                            $represent->userID = $user_repr->uid;
                            $represent->save();

                            $repr_profile = $reprprofile->load(array('userID=?', $user_repr->uid));
                            if(empty($repr_profile))
                            {
                                $reprprofile->userID = $user_repr->uid;
                                $reprprofile->civilite = $post['reprgender'];
                                $reprprofile->nom = $post['reprnom'];
                                $reprprofile->prenom = $post['reprprenom'];
                                $reprprofile->save();
                                $msg .= 'Profil du représentant créé, ';
                            }
                            $msg .= 'Représentant créé, ';
                        }
                    }
                    if(!empty($post['accemail']))
                    {
                        $acc = new Users($this->db);
                        $accprofile = new UserProfile($this->db);
                        $amail = $post['accemail'];
                        $user_acc = $acc->load(array('email=?', $amail));
                        if(empty($user_acc))
                        {
                            $acc->email = $amail;
                            $acc->password = Encrypt::load()->proceed($this->f3->get('db_pass'));
                            $acc->level = 3;
                            $acc->creatorUid = $hostid;
                            $acc->createdAt = date('Y-m-d H:i:s');
                            $acc->save();
                            $user_acc = $acc->load(array('email=?', $amail));
                            $msg .= 'Compte de l\'accompagnant créé, ';
                        }

                        $acc_profile = $accprofile->load(array('userID=?', $user_acc->uid));
                        if(empty($acc_profile))
                        {
                            $accprofile->userID = $user_acc->uid;
                            $accprofile->civilite = $post['accgender'];
                            $accprofile->nom = $post['accnom'];
                            $accprofile->prenom = $post['accprenom'];
                            $accprofile->save();
                            $msg .= 'Profil de l\'accompagnant créé, ';
                        }

                        $invitation_accompany = $accompany->load(array('accompanyingID=? AND invitationID=? AND eventID=?', $user_acc->uid, $invitation->iid, $params->eid));
                        if(empty($invitation_accompany))
                        {
                            $accompany->accompanyingID = $user_acc->uid;
                            $accompany->invitationID = $invitation->iid;
                            $accompany->eventID = $params->eid;
                            $accompany->save();
                            $msg .= 'Accompagnant créé, ';
                        }
                    }

                    $user_profile->civilite = $post['civilite'];
                    $user_profile->nom = $post['nom'];
                    $user_profile->prenom = $post['prenom'];
                    $user_profile->save();
                    $msg2 = '<b>' . strtoupper($user_profile->nom).' '.ucfirst($user_profile->prenom).'</b> : Profil complété, ';
                    $user_job->adresse = $post['adresse'];
                    $user_job->ville = $post['ville'];
                    $user_job->cp = $post['cp'];
                    $user_job->pays = $post['pays'];
                    $user_job->portable = $post['portable'];
                    $user_job->fixe = $post['fixe'];
                    $user_job->fonction = $post['fonction'];
                    $user_job->societe = $post['societe'];
                    $user_job->save();
                    $msg = $msg2 . 'Infos professionnels créés, ' . $msg;
                    $this->setMessage($msg);
                    // $this->f3->reroute('/event/'.$params->eid.'/show/guests');
                    $this->f3->reroute('/user/'.$user_exists->uid.'/show');
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
                            'view' => 'user/form/requiredprofile.htm'
                        )
                    );
                }
            } else {
                $this->f3->mset(
                    array(
                        'event' => $e,
                        'isold' => ($e->limitB < date('Y-m-d H:i:s') ? true : false),
                        'post_has_data' => '',
                        'view' => 'user/form/requiredprofile.htm'
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
     * removeGuest
     * /event/@eid/remove/guest
     */
    public function removeGuest()
    {
        $aevents = json_decode($this->f3->get('SESSION.events'));
        if( (in_array($this->f3->get('SESSION.lvl'), array(2,3)) && in_array($this->f3->get('POST.eventID'), $aevents)) || $this->f3->get('SESSION.lvl')==1 )
        {
            if($this->f3->exists('POST.delg'))
            {
                $guest = new Invitations($this->db);
                $guest->delete($this->f3->get('POST.invitationID'));
                $msg = ucfirst($this->T('invitation')).' '.$this->T('deleted_fem').', <b class="textracap">'.$this->f3->get('POST.guestname').'</b> '.$this->T('removed').' '.$this->T('from_event');
                $this->setMessage($msg);
            }
        }
        $this->f3->reroute('/event/'.$this->f3->get('POST.eventID').'/show/guests');
    }

}

