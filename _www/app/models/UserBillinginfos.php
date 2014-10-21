<?php

/**
 * UserBillinginfos Model
 * CRUD (Create, Update, Delete) UserBillinginfos table
 */
class UserBillinginfos extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'userbillinginfos');
    }


    /* ------------ READ ------------ */
    /**
     * Retrieve a list of billing infos by param
     * @param  str $param param name
     * @param  mixed $value param value
     * @return obj        list of users billing infos
     */
    public function getByFilters($param=null, $value=null) {
       $this->load(($param===null ? null : array($param.'=?',$value)));
       return $this->query;
    }


    /* ------------ CREATE ------------ */
 	/**
 	 * Add a user billing infos from post datas
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
 	 * Update a user billing infos
 	 * @param  integer $ubid id of the specified user
 	 * @return array     an array of the user infos
 	 */
    public function edit($ubid) {
        $this->load(array('ubid=?',$ubid));
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
 	 * Delete a user billing infos
 	 * @param  int $ubid id of the specified user billing infos
 	 * @return [type]     [description]
 	 */
    public function delete($ubid) {
        $this->load(array('ubid=?',$ubid));
        $this->erase();
    }

}
