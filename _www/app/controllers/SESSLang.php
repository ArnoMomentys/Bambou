<?php 

/**
 * SESSLang controller class
 */
class SESSLang extends AuthController {

	/**
	 * Check lang before route
	 */
	public function set() { 
        // Set lang session
        $this->f3->set('SESSION.lang', $this->f3->get('PARAMS.lang'));
        $this->f3->set('LANGUAGE', $this->f3->get('PARAMS.lang'));
        $this->f3->clear('SESSION.dict');
        $this->f3->reroute('/');
	}
}
