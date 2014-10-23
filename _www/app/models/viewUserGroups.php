<?php

/**
 * viewUserGroups Model
 */
class viewUserGroups extends MyMapper {


    public function __construct(DB\SQL $db) {
        parent::__construct($db, '_user_groups');
    }

	public function getByFilters($filters = NULL) {
		$filters = ($filters===NULL ? '' : array( $filters[0].' LIKE ?', '%'.$filters[1].'%'));
		$results = $this->find($filters);
		$users = array();
		foreach($results as $user)
			$users[$user->uid][] = $user;

		return $users;
    }

    public function getIn($key, $values) {
		$filters = array($key." IN ?", "('".implode("','", $values)."')");
		$results = $this->find($filters, array('order'=>'nom ASC'));
		$groups = array();
		foreach($results as $profile)
			$groups[$profile->uid][] = $profile;

		return $groups;
    }

    public function getGroupsByUserID($uid) {
    	return $this->findone(array('userID=?',$uid));
    }

}
