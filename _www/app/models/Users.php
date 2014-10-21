<?php

/**
 * Users Model
 * CRUD the users table
 * No need more explanation otherwise go check what an ORM is. Thank you.
 */
class Users extends DB\SQL\Mapper {


    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'users');
    }


 	/**
 	 * Add a user from post datas to mapper
 	 * And save it to users table
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
		$this->password = Encrypt::load()->proceed($this->password);
		$this->createdAt = date('Y-m-d H:i:s');
        $this->save();
    }


	/**
	 * Edit users table datas
	 * @param  int $uid user uid
	 * @return obj      user update status
	 */
    public function edit($uid) {
        $this->load(array('uid=?',$uid));
        // $this->copyFrom('POST');
        $newData = [];
        $this->copyFrom('POST', function($data) {
            foreach ($data as $key => $value) {
                $newData[$key] = Controller::sanitizeDatas($value);
            }
            return $newData;
        });
        if($this->password) $this->password = Encrypt::load()->proceed($this->password);
        $this->updatedAt = date('Y-m-d H:i:s');
        $this->updatedBy = $this->f3->get('SESSION.uid');
        $this->update();
    }


    /**
     * Delete function
     * @param  int $uid user uid
     * @return bool     deletion status
     */
    public function delete($uid) {
        $this->load(array('uid=?',$uid));
        $this->erase();
    }

}
