<?php 

/**
 * Auth controller class
 */
class AuthController extends Controller {

	/**
	 * Check session before route
	 * 
	 * 	session name variable
	 *	check no session name
	 *		set the loggedin variable to false
	 *		reroute to login
	 *	session name
	 *		set the loggedin variable to true
	 *		set the language
	 */
	public function beforeroute() 
	{ 
		$sess = $this->f3->get('SESSION.name');
		if(empty($sess)) 
		{ 
			$this->f3->set('loggedin', false);
			$this->f3->reroute('/login');
		} 
		else 
		{
			$this->f3->set('loggedin', true);
			if(!$this->f3->get('SESSION.lang'))
				$this->f3->set('SESSION.lang','fr');

			$l_min = $this->f3->get('SESSION.lang');
			$this->f3->set('l', $l_min);
			$l_maj = $l_min=='en'?'us':$l_min;
			$ll = $l_min.'_'.strtoupper($l_maj);
			setlocale (LC_TIME, $ll);
		}
	}


	/**
     * Check credentials
     */
    private static function acl() 
    { 
    	$arg = func_get_arg(0);
    	$c = array(
    		'event_create' => array(1,2),
    		'event_read' => array(1,2,3),
    		'event_update' => array(1,2),
    		'event_delete' => array(1,2),
    		'user_create' => array(2,3),
    		'user_read' => array(1,2,3),
    		'user_update' => array(1,2,3),
    		'user_delete' => array(1,2,3),
    		'invitation_create' => array(1,2,3),
    		'invitation_update' => array(1,2,3),
    		'invitation_delete' => array(1,2,3)
    	);
    	if(!in_array($this->f3->get('SESSION.lvl'), $c[$arg]))
    		$this->f3->reroute('/');
    		return false;

		return true;
    }

}
