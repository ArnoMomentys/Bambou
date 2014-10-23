<?php

/**
 * Events Model
 * CRUD the Events table
 */
class Events extends MyMapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'events');
    }

 	/**
 	 * Add an event from post datas
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
        $this->debut = date('Y-m-d', strtotime($this->debut));
        $this->fin = date('Y-m-d', strtotime($this->fin));
        $this->limitA = date('Y-m-d', strtotime($this->limitA));
        $this->limitB = date('Y-m-d', strtotime($this->limitB));
        $this->deadLine = date('Y-m-d', strtotime($this->deadLine));
        $this->save();
    }

    /**
     * Retrieve a list of events by param
     * @param  str $param param name
     * @param  mixed $value param value
     * @return obj        list of events
     */
    public function getByFilters($param=null, $value=null) {
       	$this->load(($param===null ? null : array($param.'=?',$value)));
       	return $this->query;
    }

	/**
	 * Edit function
	 * @param  int $eid event eid
	 * @return obj      event update status
	 */
    public function edit($eid) {
        $this->load(array('eid=?',$eid));
        // $this->copyFrom('POST');
        $newData = [];
        $this->copyFrom('POST', function($data) {
            foreach ($data as $key => $value) {
                $newData[$key] = Controller::sanitizeDatas($value);
            }
            return $newData;
        });
    	if(isset($this->status)) {
    		$this->status = $this->status == "on" ? 2 : $this->status;
    	} else {
    		$this->status = 1;
    	}
        $this->update();
    }

    /**
     * Delete function
     * @param  int $eid event eid
     * @return bool     deletion status
     */
    public function delete($eid) {
        $this->load(array('eid=?',$eid));
        return $this->erase();
    }

    public function setLastRead($eid) {
    	return $this->db->exec('UPDATE `events` SET `lastRead` = NOW() WHERE `eid` = ?', $eid);
    }

    public function getLastRead() {
    	return $this->db->exec('SELECT * FROM `events` WHERE `lastRead` = (SELECT MAX(`lastRead`) FROM `events`)');
    }

}
