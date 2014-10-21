<?php

/**
 * Connexion class
 */
class Connexion extends Controller {

	/**
	 * Array of user credentials
	 */
	private $credentials = array(
		1 => 'Administrateur',
		2 => 'Administrateur',
		3 => 'Contact GDF SUEZ',
		4 => 'Accompagnant',
		5 => 'Représentant'
	);

	/**
	 * Set the login view params
	 * And show the login form
	 * set default message or error message
	 * release the session
	 * set the loggedin variable to false, the tittle of the page and the view to call
	 */
	public function setLogin($msg=null, $msgtype='info')
    {
		$msg = $msgtype=='info' ? $this->T('connect_sub') : $msg;
    	$this->f3->clear('SESSION');
        $this->f3->mset(
        	array(
        		'msg' => $msg,
        		'msgtype' => $msgtype, // success, info, warning, danger
        		'loggedin' => false,
        		'page_head' => 'Page de Connexion',
        		'view' => 'user/form/login.htm'
        	)
        );
	}


    /**
     * Perform a form login action
	 *
	 * Perform a map login
     *  Transform post variables
	 *  Map to a user in database
     *  crypt password to match database
     *  load object mapped
     *  check emptyness or show login form
     *  set session name
     *  update login date
     *  set session vars
     *  redirect to homepage
	 *  Call the login form
     */
    public function logIn()
    {
	    if( $this->f3->exists('POST.login')
	    		&& $this->f3->get('POST.ide')!==''
	    			&& $this->f3->get('POST.pw')!=='' ) {
            $mail = $this->f3->get('POST.ide');
	    	$pw = $this->f3->get('POST.pw');
        	$auth = new Users($this->db);
			$pwout = Encrypt::load()->proceed($pw);
            $auth->load(array('email=? && password=?', $mail, $pwout));
            if ( !$auth->dry() ) {
        		$auth->loggedAt = date('Y:m:d H:i:s');
        		$auth->update();
        		$profile = new viewUserCompleteProfile($this->db);
        		$user = $profile->getUserFullProfileByUid($auth->uid);
    			$aevents = array();
        		if($auth->level > 2) {
        			$events = new viewEventHostsInfos($this->db);
        			$myevents = $events->getHostEvents($auth->uid);
        			foreach($myevents as $events) {
        				$aevents[] = $events->fields['eventid']['value'];
        			}
        		}
    			$sevents = json_encode($aevents);

    			if($auth->creatorUid==0 && $auth->level==1)
    				$creds = '[&nbsp;The Beyonder&nbsp;&#937;&nbsp;]';
    			elseif($auth->creatorUid>0 && $auth->level==1)
    				$creds = '[&nbsp;Super&nbsp;Admin&nbsp;&#945;&nbsp;]';
    			else
    				$creds = '[&nbsp;'.$this->credentials[$auth->level].'&nbsp;]';

    	    	$this->f3->mset(
    	    		array(
    	    			'SESSION.name' => $mail,
    	    			'SESSION.uid' => $auth->uid,
    	    			'SESSION.lvl' => $auth->level,
    	    			'SESSION.c' => $auth->creatorUid,
    	    			'SESSION.cred' => $creds,
    	    			'SESSION.profile.nom' => $user->nom,
    	    			'SESSION.profile.prenom' => $user->prenom,
    	    			'SESSION.events' => $sevents
    	    		)
    	    	);
                if($auth->level == 1) $this->f3->set('SESSION.switch', true);
    	    	$this->f3->set('SESSION.crp', Encrypt::load()->proceed(json_encode($this->f3->get('SESSION'), JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE)));
        		$this->f3->reroute('/');
        	} else {
        		$this->setLogin($this->T('login_error'), 'danger');
        	}
        }
        else {
	    	$this->setLogin();
        }
    }

    /**
     * Perform a logout aciton
 	 * release the session
     * set the loggedin variable to false
	 * redirect to login page
     */
    public function logOut()
    {
    	$this->f3->clear('SESSION');
		$this->f3->set('loggedin', false);
        $this->f3->reroute('/login');
    }

}
