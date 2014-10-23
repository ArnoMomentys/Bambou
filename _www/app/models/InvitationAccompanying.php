<?php

/**
 * InvitationAccompanying Model
 * CRUD (Create, Read, Update, Delete) invitationAccompanying table
 */
class InvitationAccompanying extends MyMapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'accompanying');
    }


    /* ------------ READ ------------ */
    /**
     * Retrieve a list of accompanying by param
     * @param  str $param param name
     * @param  mixed $value param value
     * @return obj        list of accompanying
     */
    public function getByFilters($param=null, $value=null) {
       $this->load(($param===null ? null : array($param.'=?',$value)));
       return $this->query;
    }





    /* ------------ CREATE ------------ */
 	/**
 	 * Add a user's accompanying from post datas
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
 	 * Update user's accompanying
 	 * @param  integer $iaid id of the specified user's accompanying
 	 * @return
 	 */
    public function edit($iaid) {
        $this->load(array('iaid=?',$iaid));
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
 	 * Delete a user's accompanying
 	 * @param  int $iaid id of the specified user's accompanying
 	 * @return
 	 */
    public function delete($iaid) {
        $this->load(array('iaid=?',$iaid));
        $this->erase();
    }

}

