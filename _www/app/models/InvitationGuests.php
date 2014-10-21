<?php

/**
 * InvitationGuests Model
 * CRUD (Create, Read, Update, Delete) invitationGuests table
 */
class InvitationGuests extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'invitationguests');
    }


    /* ------------ READ ------------ */
    /**
     * Retrieve a list of invitations by param
     * @param  str $param param name
     * @param  mixed $value param value
     * @return obj        list of invitations
     */
    public function getByFilters($param=null, $value=null) {
       $this->load(($param===null ? null : array($param.'=?',$value)));
       return $this->query;
    }


    /* ------------ CREATE ------------ */
 	/**
 	 * Add an invitation from post datas
 	 */
    public function add($iid=null) {
    	$this->reset();
    	if(!empty($iid))
    		$this->invitationID = $iid;

    	// $this->copyFrom('POST');
        $newData = [];
        $this->copyFrom('POST', function($data) {
            foreach ($data as $key => $value) {
                $newData[$key] = Controller::sanitizeDatas($value);
            }
            return $newData;
        });

        $this->save();
        return $this;
    }


	/* ------------ UPDATE ------------ */
 	/**
 	 * Update an invitation
 	 * @param  integer $iid id of the specified invitation
 	 * @return
 	 */
    public function edit($iid) {
        $this->load(array('iid=?',$iid));
        $this->copyFrom('POST');
        $this->update();
    }


    /* ------------ DELETE ------------ */
 	/**
 	 * Delete an invitation
 	 * @param  int $iid id of the specified invitation
 	 * @return
 	 */
    public function delete($iid) {
        $this->load(array('iid=?',$iid));
        $igid = $this->igid;
        $this->erase();
    }

}
