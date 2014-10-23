<?php

/**
 * Invitations Model
 * CRUD (Create, Read, Update, Delete) invitations table
 */
class Invitations extends MyMapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'invitations');
    }


    /* ------------ READ ------------ */
    /**
     * Retrieve a list of invitations by param
     * @param  str $param param name
     * @param  mixed $value param value
     * @return obj        list of invitations
     */
    public function getByFilters($param=null, $value=null, $options=null) {
       $this->load($param===null ? null : array($param.'=?',$value),$options);
       return $this->query;
    }


    /**
     * [getOneByGuestIDhostIDEventID description]
     * @param  [type] $hostid  [description]
     * @param  [type] $guestid [description]
     * @param  [type] $eventid [description]
     * @return [type]          [description]
     */
    public function getOneByGuestIDhostIDEventID($hostid, $guestid, $eventid) {
    	return $this->findone(array('hostID=? AND guestID=? AND eventID=?', $hostid, $guestid, $eventid));
    }

    /**
     * [getOneByIidFlat doesnt hydrate the mapper -> faster]
     * @param  int $iid [description]
     * @return [type]           [description]
     */
    public function getOneByIidFlat($iid) {
    	return $this->db->exec("SELECT * FROM `invitations` WHERE `iid` = ?", $iid);
    }

    /* ------------ CREATE ------------ */
 	/**
 	 * Add an invitation from post datas
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
        return $this;
    }


	/* ------------ UPDATE ------------ */
 	/**
 	 * Update an invitation
 	 * @param  integer $iid id of the specified invitation
 	 * @return
 	 */
    public function edit($iid) {
        $this->load(array('iid=?',$iid));
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
 	 * Delete an invitation
 	 * @param  int $iid id of the specified invitation
 	 * @return
 	 */
    public function delete($iid) {
        $this->load(array('iid=?',$iid));
        $this->erase();
        $ig = new DB\SQL\Mapper($this->db, 'invitationguests');
        $ig->load(array('invitationID = ?', $iid));
        $ig->erase();
    }


    public function selectMulti($params,$field=null) {
        $f = $field==null ? '*' : $field;
        return $this->db->exec('SELECT '.$f.' FROM `invitations` WHERE '.$params);
    }

    public function deleteMulti($iids) {
    	$aiids = count($iids)>0 ? implode(',', $iids) : $iids[0];
        return $this->db->exec('DELETE FROM `invitations` WHERE `iid` IN ('.$aiids.')');
    }

}
