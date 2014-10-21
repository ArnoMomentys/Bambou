<?php

/**
 * InvitationRepresentative Model
 * CRUD (Create, Read, Update, Delete) invitationRepresentative table
 */
class InvitationRepresentative extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'invitationrepresentative');
    }


    /* ------------ READ ------------ */
    /**
     * Retrieve a list of representatives by param
     * @param  str $param param name
     * @param  mixed $value param value
     * @return obj        list of representatives
     */
    public function getByFilters($param=null, $value=null) {
       $this->load(($param===null ? null : array($param.'=?',$value)));
       return $this->query;
    }


    /* ------------ CREATE ------------ */
 	/**
 	 * Add a user's representative from post datas
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
 	 * Update user's representative
 	 * @param  integer $irid id of the specified user's representative
 	 * @return
 	 */
    public function edit($irid) {
        $this->load(array('irid=?',$irid));
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
 	 * Delete a user's representative
 	 * @param  int $irid id of the specified user's representative
 	 * @return
 	 */
    public function delete($irid) {
        $this->load(array('irid=?',$irid));
        $this->erase();
    }

}

