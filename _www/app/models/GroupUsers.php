<?php

/**
 * GroupUsers Model
 * CRUD (Create, Read, Update, Delete) groupUsers table
 */
class GroupUsers extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'groupusers');
    }


    /* ------------ READ ------------ */
    /**
     * Retrieve a list of user groups by param
     * @param  str $param param name
     * @param  mixed $value param value
     * @return obj        list of user group
     */
    public function getList($param=null, $value=null) {
       $this->load(($param===null ? null : array($param.'=?',$value)));
       return $this->query;
    }


    public function getUsersInGroupByGroupID($value) {
    	$usergroup = $this->find(array('groupID=?',$value), array('order'=>'userID'));
    	$usergroupuid = array();
    	foreach($usergroup as $group)
    		$usergroupuid[$group->get('guid')] = $group->get('userID');

    	return $usergroupuid;
    }


    /* ------------ CREATE ------------ */
 	/**
 	 * Add a user's group from post datas
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
 	 * Update user's group
 	 * @param  integer $guid id of the specified user's group
 	 * @return
 	 */
    public function edit($guid) {
        $this->load(array('guid=?',$guid));
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
 	 * Delete a user's group
 	 * @param  int $guid id of the specified user's group
 	 * @return
 	 */
    public function delete() {
    	$arg = func_get_arg(0);
    	if(is_array($arg)) {
    		$this->load(array('groupID=? AND userID=?', $arg[0], $arg[1]));
    	} else {
        	$this->load(array('guid=?',$param));
    	}
        $this->erase();
    }

}

