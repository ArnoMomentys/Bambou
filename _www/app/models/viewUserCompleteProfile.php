<?php

/**
 * viewUserCompleteProfile Model
 */
class viewUserCompleteProfile extends DB\SQL\Mapper {


    public function __construct(DB\SQL $db) {
        parent::__construct($db, '_complete_user_profile');
    }


    public function getUsersProfilesFiltered_Paginated($filters, $options) {
        $page = \Pagination::findCurrentPage();
        // return $this->paginate($page-1, 50, $filters, $options);
        return $this->paginateToArray($page-1, 50, $filters, $options);
    }


    public function getUsersProfilesWithUidInListFiltered_Paginated($filters, $options) {
        $page = \Pagination::findCurrentPage();
        return $this->paginateToArray($page-1, 30, $filters, $options);
    }


    public function getUsersProfilesWithUidNOTInListFiltered_Paginated($filters, $options) {
        $page = \Pagination::findCurrentPage();
        return $this->paginateToArray($page-1, 50, $filters, $options);
    }


    public function getUsersMinimumProfileAliasedByUidsIn_Raw($values) {
    	return $this->db->exec("SELECT `civilite` as `Civilite`, `nom` as `Nom`, `prenom` as `Prénom`, `fonction` as `Fonction`, `societe` as `Société`, `branche` as `Branche`, `adresse` as `Adresse`, `cp` as `Code Postal`, `ville` as `Ville`, `pays` as `Pays`, `portable` as `Tél Portable`, `fixe` as `Tél Fixe`, case when `email` like '%@nielsy.com' then null else `email` end as `Adresse mail` FROM `_complete_user_profile` WHERE `uid` IN ('".implode("','", $values)."') ORDER BY `nom` ASC");
    }


    public function getUsersMinimumProfileByUidsIn_Raw($uids) {
    	return $this->db->exec("SELECT `uid`, `civilite`, `nomcomplet`, `nom`, `prenom`, `fonction`, `societe`, `branche`, `adresse`, `cp`, `ville`, `pays`, `portable`, `fixe`, case when `email` like '%@nielsy.com' then null else `email` end as email FROM `_complete_user_profile` WHERE `uid` IN ('".implode("','", $uids)."') ORDER BY `nom` ASC");
    }

    public function getUsersMinimumProfileExportByUidsIn_Raw($uids) {
    	return $this->db->exec("SELECT `civilite`, `nom`, `prenom`, `fonction`, `branche`, `BU` as bu, `societe`, `adresse`, `cp` as code_postal, `ville`, `pays`, `fixe` as tel_fixe, `portable` as tel_portable, case when `email` like '%@nielsy.com' then null else `email` end as adresse_mail FROM `_complete_user_profile` WHERE `uid` IN ('".implode("','", $uids)."') ORDER BY `nom` ASC");
    }
    
    public function getLastLoggedUserFullProfile() {
    	return $this->findone(
    		array('level>1'),
    		array(
    			'order'=>'loggedAt DESC',
    			'limit'=>1
    			)
    		);
    }


    public function getLastCreatedUserFullProfile() {
    	return $this->findone(
    		array('1=1'),
    		array(
    			'order'=>'createdAt DESC',
    			'limit'=>1
    			)
    		);
    }


    public function getUserFullProfileByUid($uid) {
    	return $this->findone(array('uid=?', $uid));
    }


    public function getUserFullProfileByUid_Raw($uid) {
    	return $this->db->exec(
    		"SELECT * FROM `_complete_user_profile` WHERE `uid` = ?",
    		$uid
    	);
    }
    
    public function getUserExportProfileByUid_Raw($uid) {
    	return $this->db->exec(
    			"SELECT `nom`, `prenom`, `societe`, `branche` FROM `_complete_user_profile` WHERE `uid` = ?",
    			$uid
    	);
    }


    /**
     * retrieve all users uids without hydrating the mapper
     * @return array    users uids array
     */
    public function getUsersUids_Raw() {
       return $this->db->exec('SELECT `uid` FROM users ORDER BY `uid` ASC');
    }


    /**
     * retrieve one user by uid
     * @return object   user object
     */
    public function getUserByUid($uid) {
        return $this->findone(array('uid=?', $uid));
    }


    /**
     * retrieve one user by email
     * @return object   user object
     */
    public function getUserByEmail($email) {
        return $this->findone(array('email=?', $email));
    }


}
