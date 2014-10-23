<?php

/**
 * EventHosts Model
 * CRUD (Create, Read, Update, Delete) EventHosts table
 */
class EventHosts extends MyMapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'eventhosts');
    }


    /* ------------ READ ------------ */
    public function getByFilters($param=null, $value=null) {
       $this->load(($param===null ? null : array($param.'=?',$value)));
       return $this->query;
    }


    public function add() {
        $newData = [];
        $this->copyFrom('POST', function($data) {
            foreach ($data as $key => $value) {
                $newData[$key] = Controller::sanitizeDatas($value);
            }
            return $newData;
        });
        $this->save();
    }


    public function edit($ehid) {
        $this->load(array('ehid=?',$ehid));
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


    public function delete() {
		$arg = func_get_arg(0);
        if(is_array($arg)) {
            $eh = $this->load(array('eventID=? AND hostID=?', $arg[0], $arg[1]));
        } else {
            $eh = $this->load(array('ehid=?',$param));
        }
        $eid = $eh->eventID;
        $hostid = $eh->hostID;
        $this->erase();

        $invitations = new Invitations($this->db);
        $count_invitations = $invitations->count(array('hostID=? AND eventID=?', $hostid, $eid));

        $aIID = [];
        if($count_invitations > 0) {
            $invitations_select = $invitations->select('iid', array('hostID=? AND eventID=?', $hostid, $eid));
            foreach($invitations_select as $invitation) {
                $aIID[] = $invitation->iid;
            }
            $invitations->deleteMulti($aIID);
        }

    }

}
