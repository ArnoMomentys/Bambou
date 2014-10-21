<?php

/**
 * UserProfile Model
 * CRUD (Create, Update, Delete) userProfile table
 */
class UserProfile extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'userprofile');
    }


    /* ------------ CREATE ------------ */
 	/**
 	 * Add a userprofile from post datas
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
 	 * Update a userprofile
 	 * @param  integer $uid id of the specified user
 	 * @return array     an array of the user infos
 	 */
    public function edit($upid) {
        $this->load(array('upid=?',$upid));
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
 	 * Delete a userprofile
 	 * @param  int $uid id of the specified user
 	 * @return [type]     [description]
 	 */
    public function delete($upid) {
        $this->load(array('upid=?',$upid));
        $this->erase();
    }

}
