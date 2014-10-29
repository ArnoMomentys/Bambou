<?php

/**
 * Users Model
 * CRUD the users table
 * No need more explanation otherwise go check what an ORM is. Thank you.
 */
class Users extends MyMapper {

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
        $this->update();
    }

    public function insert()
    {
        $this->createdAt = date('Y-m-d H:i:s');

        $this->_setHashIfNeeded();
       parent::insert();
    }

    public function update()
    {
        $f3 = Base::instance();

        $this->updatedAt = date('Y-m-d H:i:s');
        $this->updatedBy = $f3->get('SESSION.uid');
        $this->_setHashIfNeeded();
        parent::update();
    }

    private function _setHashIfNeeded()
    {
        $f3 = Base::instance();

        $post = array_map('trim', $f3->get('POST'));

        // !isset on a zero length string gives unexpected result, we need to check definition AND length
        if (empty($this->hash) && isset($post['nom']) && isset($post['prenom']) && isset($post['societe']))
        {
            $this->hash = MyMapper::getUserHash($post);
        }
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
