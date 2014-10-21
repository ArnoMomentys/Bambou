<?php

/**
 * UserContacts Model
 * CRUD (Create, Update, Delete) userContacts table
 */
class UserContacts extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'usercontacts');
    }


    /**
     * Récupérer les contacts d'un utilisateur en particulier
     * qui n'est pas l'utisateur courant
     * @return obj user list
     */
    public function getHostContacts($hostid) {
    	return $this->getByFilters('hostID', $hostid);
    }



    /**
     * Récupérer les contacts d'un utilisateur en particulier
     * qui n'est pas l'utisateur courant
     * @return array user id list
     */
    public function getHostContactsIds($hostid) {
    	return $this->find(array('hostID=?', $hostid));
    }



    /**
	 *
     */
    public function getHostIDContactID($hostid, $contactid) {
    	return $this->find(array('hostID=? AND contactID=?', $hostid, $contactid));
    }



    /* ------------ READ ------------ */
    /**
     * Retrieve a list of users contacts by param
     * @param  str $param param name
     * @param  mixed $value param value
     * @return obj        list of users contacts
     */
    public function getByFilters($param=null, $value=null) {
       $this->load(($param===null ? null : array($param.'=?', $value)));
       return $this->query;
    }


    /* ------------ CREATE ------------ */
 	/**
 	 * Add a user contacts from post datas
 	 */
    public function add() {
        // $this->copyFrom('POST');
        $newData = [];
        $this->copyFrom('POST', function($data) {
            foreach ($data as $key => $value) {
                $newData[$key] = Controller::sanitizeDatas($value);
            }
            return $newData;
        });
        $this->save();
    }


	/* ------------ UPDATE ------------ */
 	/**
 	 * Update a user contacts
 	 * @param  integer $ucid id of the specified user
 	 * @return array     an array of the user infos
 	 */
    public function edit($ucid) {
        $this->load(array('ucid=?', $ucid));
        // $this->copyFrom('POST');
        $newData = [];
        $this->copyFrom('POST', function($data) {
            foreach ($data as $key => $value) {
                $newData[$key] = Controller::sanitizeDatas($value);
            }
            return $newData;
        });
        $this->update();
    }


    /* ------------ DELETE ------------ */
 	/**
 	 * Delete a user contacts
 	 * @param  int $ucid id of the specified user contacts
 	 * @return [type]     [description]
 	 */
    public function delete($ucid) {
        $this->load(array('ucid=?', $ucid));
        $this->erase();
    }

}
