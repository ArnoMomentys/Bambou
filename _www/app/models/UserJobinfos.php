<?php

/**
 * UserJobinfos Model
 * CRUD (Create, Update, Delete) userJobinfos table
 */
class UserJobinfos extends MyMapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'userjobinfos');
    }


    /* ------------ CREATE ------------ */
 	/**
 	 * Add a user job infos from post datas
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
 	 * Update a user job infos
 	 * @param  integer $ujid id of the specified user
 	 * @return array     an array of the user infos
 	 */
    public function edit($ujid) {
        $this->load(array('ujid=?',$ujid));
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
 	 * Delete a user job infos
 	 * @param  int $ujid id of the specified user job infos
 	 * @return [type]     [description]
 	 */
    public function delete($ujid) {
        $this->load(array('ujid=?',$ujid));
        $this->erase();
    }

}
