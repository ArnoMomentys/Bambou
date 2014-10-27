<?php

/**
 * Invitations controller class
 */
class InvitationsController extends AuthController {

    /**
     * validate an invitation
     * /event/@eid/invitation/validate
     */
    public function validateInvitation()
    {
    	$aevents = json_decode($this->f3->get('SESSION.events'));
    	if( (in_array($this->f3->get('SESSION.lvl'), array(2,3)) && in_array($this->f3->get('POST.eventID'), $aevents)) || $this->f3->get('SESSION.lvl')==1 )
    	{
    		if($this->f3->exists('POST.validate-guest'))
    		{
	    		$invitations = new Invitations($this->db);
	    		$invitations->load(array('iid=?',$this->f3->get('POST.invitationID')));
	    		$invitations->validated = 1;
	    		$invitations->validatedAt = date('Y-m-d H:i:s');
	    		$invitations->save();
	    		$msg = ucfirst($this->T('invitation')).' '.$this->T('validated_fem').' '.$this->T('for').' <b class="textracap">'.$this->f3->get('POST.guestname').'</b>';
	    		$this->setMessage($msg);
	    	}
    	}
		$this->f3->reroute('/event/'.$this->f3->get('POST.eventID').'/show/guests');
    }

    /**
     * set answer to invitation
     * /event/@eid/guest/@iid/@answer/@ref
     */
    public function setAnswer()
    {
        $params = (object) array_map('trim', $this->f3->get('PARAMS'));
		if( isset($params->eid) && isset($params->iid) && isset($params->answer) && isset($params->ref) )
		{
		    $iid = explode(",", $params->iid);
		    foreach($iid as $currentIID)
		    {
    			// mettre à jour l'invitation
        		$invitation = new InvitationGuests($this->db);
        		$invitation->load(array('invitationID=?', $currentIID));
        		$invitation->guestAnswer = ($params->answer > 2 || $params->answer <= 0 ? 0 : $params->answer);
        		$invitation->answeredAt = date('Y-m-d H:i:s');
        		$invitation->save();
		    }
    		// recupérer le nom de l'invité pour le message de retour
    		$invite = new Invitations($this->db);
    		$i = $invite->getOneByIidFlat($iid[0]);
    		$guest = new viewUserCompleteProfile($this->db);
    		$u = $guest->getUserFullProfileByUid_Raw($i[0]['guestID']);
    		$msg = $this->T('answer_to_invitation_saved').($params->answer > 2 || $params->answer <= 0 ? '' : (' <b class="textracap">'.$u[0]['nom'].' '.$u[0]['prenom'].'</b> '.($params->answer==1 ? $this->T('guest_answer_yes') : $this->T('guest_answer_no'))));
    		$this->setMessage($msg);
    		$route = implode('/', explode('_', $params->ref));
    		$this->f3->reroute('/'.$route);
    	}
		$this->f3->reroute('/event/'.$params->eid.'/show/guests');
    }

    /**
     * set presence
     * /event/@eid/guest/@iid/presence/@presence/@ref
     */
    public function setPresence()
    {
		if( $this->f3->get('SESSION.lvl') <= 3 )
		{
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
    		if( isset($params->eid) && isset($params->iid) && isset($params->presence) && isset($params->ref) )
    		{
    			// mettre à jour l'invitation
	    		$invitation = new InvitationGuests($this->db);
	    		$invitation->load(array('invitationID=?', $params->iid));
	    		$invitation->guestPresence = $params->presence;
	    		$invitation->save();
	    		// recupérer le nom de l'invité pour le message de retour
	    		$invite = new Invitations($this->db);
	    		$i = $invite->getOneByIidFlat($params->iid);
	    		$guest = new viewUserCompleteProfile($this->db);
	    		$u = $guest->getUserFullProfileByUid_Raw($i[0]['guestID']);
	    		$msg = ($params->presence==1 ? $this->T('presence_to_event_saved') : $this->T('absence_to_event_saved')).' '.$this->T('for').' <b class="textracap">'.$u[0]['nom'].' '.$u[0]['prenom'].'</b> ';
	    		$this->setMessage($msg);
	    	}
    	}
		$this->f3->reroute('/event/'.$params->eid.'/show/guests');
    }

    /**
     * create/check user account
     * create/check user profile
     * add accompanying to invitation
     *
     * check credentials (1)
     *      check POST param addAcc (2)
     *          store POST in $datas (3)
     *          init check instance (4)
     *          foreach datas do check (5)
     *              check datas length (6)
     *              check email format (7)
     *          check validation results (8)
     *
     */
    public function addAccompanying()
    {
        if( $this->f3->get('SESSION.lvl')<=3 )  // 1
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS')); // @eid, @guestid, @invitationid     // 9
            if($this->f3->exists('POST.addAcc'))    // 2
            {
                $post = array_map('trim', $this->f3->get('POST')); // 3
                $check = new Test(1);   // 4
                foreach($post as $post_index => $post_value)   // 5
                {
                    if(in_array($post_index, array('civilite','nom','prenom','email'))) // 6
                    {
                        $t_text = 'user_'.$post_index.'_required';
                        $check->expect(strlen($post_value)===1, $t_text);
                    }
                    if($post_index=='email') // 7
                    {
                        $t_email = 'user_'.$post_index.'_format';
                        $check->expect(filter_var($post_value, FILTER_VALIDATE_EMAIL)===false, $t_email);
                    }
                }
                $r = $check->results();
                if(empty($r))   // 8
                {
                    $msg = '';
                    $acc = new Users($this->db);    // 10
                    $accprofile = new UserProfile($this->db);   // 11
                    $accompany = new InvitationAccompanying($this->db);    // 12
                    $amail = $post['email'];      // 13

                    $user_acc = $acc->load(array('email=?', $amail));   // 14
                    if(empty($user_acc))    // 15
                    {
                        $acc->email = $amail;   // 16
                        $acc->password = Encrypt::load()->proceed($this->f3->get('db_pass'));
                        $acc->level = 4;
                        $acc->creatorUid = $this->f3->get('SESSION.uid');
                        $acc->createdAt = date('Y-m-d H:i:s');
                        $acc->save();   // 17
                        $user_acc = $acc->load(array('email=?', $amail));   // 18
                        $msg .= 'Compte de l\'accompagnant créé. ';     // 19
                    }

                    $acc_profile = $accprofile->load(array('userID=?', $user_acc->uid));    // 20
                    if(empty($acc_profile))     // 21
                    {
                        $accprofile->userID = $user_acc->uid;   // 22
                        $accprofile->civilite = $post['civilite'];
                        $accprofile->nom = $post['nom'];
                        $accprofile->prenom = $post['prenom'];
                        $accprofile->save();    // 23
                        $msg .= 'Profil de l\'accompagnant créé. ';     // 24
                    }

                    $invitation_accompany = $accompany->load(array('accompanyingID=? AND invitationID=? AND eventID=?', $user_acc->uid, $params->invitationid, $params->eid));
                    if(empty($invitation_accompany)) {
                        $accompany->accompanyingID = $user_acc->uid;
                        $accompany->invitationID = $params->invitationid;
                        $accompany->eventID = $params->eid;
                        $accompany->save();
                        $msg .= 'Accompagnant créé.';
                    }
                    $this->setMessage($msg);
                    $this->f3->reroute('/event/'.$params->eid.'/show/guests');
                }
                else
                {
                    while (list(, $value) = each($r))
                    {
                        list($y, $i, $x) = explode('_', $value['text']);
                        $this->errors($this->T($value['text']));
                    }
                    $eventObj = new Events($this->db);
                    $event = $eventObj->load(array('eid=?',$params->eid));
                    $userProfileObj = new UserProfile($this->db);
                    $guest = $userProfileObj->load(array('userID=?',$params->guestid));
                    $this->f3->mset(
                        array(
                            'event' => $event,
                            'guest' => $guest,
                            'iid' => $params->invitationid,
                            'post_has_data' => $post,
                            'view' => 'event/form/addaccompanying.htm'
                        )
                    );
                }
            }
            else
            {
                $eventObj = new Events($this->db);
                $event = $eventObj->load(array('eid=?',$params->eid));
                $userProfileObj = new UserProfile($this->db);
                $guest = $userProfileObj->load(array('userID=?',$params->guestid));
                $this->f3->mset(
                    array(
                        'event' => $event,
                        'guest' => $guest,
                        'iid' => $params->invitationid,
                        'post_has_data' => '',
                        'view' => 'event/form/addaccompanying.htm'
                    )
                );
            }
        }
    }

    /**
     *
     */
    public function removeAccompanying()
    {
        if( $this->f3->get('SESSION.lvl')<=3 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $accompanyingObj = new InvitationAccompanying($this->db);
            $acc = $accompanyingObj->load(array('eventID=? AND invitationID=? AND accompanyingID=?', $params->eid, $params->invitationid, $params->accid));
            $acc->delete($acc->iaid);
            $msg = $this->T('accompanying_removed');
            $this->setMessage($msg);
        }
        $this->f3->reroute('/event/'.$params->eid.'/show/guests');
    }

    /**
     *
     */
    public function addRepresentative()
    {
        if( $this->f3->get('SESSION.lvl')<=3 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS')); // @eid, @guestid, @invitationid     // 9
            if($this->f3->exists('POST.addRepr'))    // 2
            {
                $post = array_map('trim', $this->f3->get('POST')); // 3
                $check = new Test(1);   // 4
                foreach($post as $post_index => $post_value)   // 5
                {
                    if(in_array($post_index, array('civilite','nom','prenom','email'))) // 6
                    {
                        $t_text = 'user_'.$post_index.'_required';
                        $check->expect(strlen($post_value)===1, $t_text);
                    }
                    if($post_index=='email') // 7
                    {
                        $t_email = 'user_'.$post_index.'_format';
                        $check->expect(filter_var($post_value, FILTER_VALIDATE_EMAIL)===false, $t_email);
                    }
                }
                $r = $check->results();
                if(empty($r))   // 8
                {
                    $msg = '';
                    $repr = new Users($this->db);    // 10
                    $reprprofile = new UserProfile($this->db);   // 11
                    $representative = new InvitationRepresentative($this->db);    // 12
                    $rmail = $post['email'];      // 13

                    $user_repr = $repr->load(array('email=?', $rmail));   // 14
                    if(empty($user_repr))    // 15
                    {
                        $repr->email = $rmail;   // 16
                        $repr->password = Encrypt::load()->proceed($this->f3->get('db_pass'));
                        $repr->level = 4;
                        $repr->creatorUid = $this->f3->get('SESSION.uid');
                        $repr->createdAt = date('Y-m-d H:i:s');
                        $repr->save();   // 17
                        $user_repr = $repr->load(array('email=?', $rmail));   // 18
                        $msg .= 'Compte du représentant créé. ';     // 19
                    }

                    $repr_profile = $reprprofile->load(array('userID=?', $user_repr->uid));    // 20
                    if(empty($repr_profile))     // 21
                    {
                        $reprprofile->userID = $user_repr->uid;   // 22
                        $reprprofile->civilite = $post['civilite'];
                        $reprprofile->nom = $post['nom'];
                        $reprprofile->prenom = $post['prenom'];
                        $reprprofile->save();    // 23
                        $msg .= 'Profil du représentant créé. ';     // 24
                    }

                    $invitation_representative = $representative->load(array('invitationID=? AND userID=?', $params->invitationid, $user_repr->uid));
                    if(empty($invitation_representative)) {
                        $representative->invitationID = $params->invitationid;
                        $representative->userID = $user_repr->uid;
                        $representative->save();
                        $msg .= 'Représentant créé.';
                    }
                    $this->setMessage($msg);
                    $this->f3->reroute('/event/'.$params->eid.'/show/guests');
                }
                else
                {
                    while (list(, $value) = each($r))
                    {
                        list($y, $i, $x) = explode('_', $value['text']);
                        $this->errors($this->T($value['text']));
                    }
                    $eventObj = new Events($this->db);
                    $event = $eventObj->load(array('eid=?',$params->eid));
                    $userProfileObj = new UserProfile($this->db);
                    $guest = $userProfileObj->load(array('userID=?',$params->guestid));
                    $this->f3->mset(
                        array(
                            'event' => $event,
                            'guest' => $guest,
                            'iid' => $params->invitationid,
                            'post_has_data' => $post,
                            'view' => 'event/form/addrepresentative.htm'
                        )
                    );
                }
            }
            else
            {
                $eventObj = new Events($this->db);
                $event = $eventObj->load(array('eid=?',$params->eid));
                $userProfileObj = new UserProfile($this->db);
                $guest = $userProfileObj->load(array('userID=?',$params->guestid));
                $this->f3->mset(
                    array(
                        'event' => $event,
                        'guest' => $guest,
                        'iid' => $params->invitationid,
                        'post_has_data' => '',
                        'view' => 'event/form/addrepresentative.htm'
                    )
                );
            }
        }
    }

    /**
     *
     */
    public function removeRepresentative()
    {
        if( $this->f3->get('SESSION.lvl')<=3 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $representativeObj = new InvitationRepresentative($this->db);
            $repr = $representativeObj->load(array('invitationID=? AND userID=?', $params->invitationid, $params->reprid));
            $repr->delete($repr->irid);
            $msg = $this->T('representative_removed');
            $this->setMessage($msg);
        }
        $this->f3->reroute('/event/'.$params->eid.'/show/guests');
    }
}

