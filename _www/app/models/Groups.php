<?php

/**
 * Groups Model
 * CRUD (Create, Read, Update, Delete) groups table
 */
class Groups extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'groups');
    }


    /* ------------ CREATE ------------ */
 	/**
 	 * Add a group from post datas
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
 	 * Update group
 	 * @param  integer $gid id of the specified group
 	 * @return
 	 */
    public function edit($gid) {
        $this->load(array('gid=?',$gid));
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
 	 * Delete a group
 	 * @param  int $gid id of the specified group
 	 * @return
 	 */
    public function delete($gid) {
        $this->load(array('gid=?',$gid));
        $this->erase();
    }

}

