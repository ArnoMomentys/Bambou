<?php

/**
 * Personalisation du mapper  
 *
 */
class MyMapper extends DB\SQL\Mapper
{
    const SQL_ARRAY_DELIMITER = '[__¤~~¤__]';
    const SQL_ARRAY_FIELD_PREFIX = '__array__';
    const FLOAT_PRECISION = 2; 
    private static $_db = null;
    private static $_f3 = null;
    private static $_userFieldHash = ['nom', 'prenom', 'societe'];
    
    /**
     * Call the sanitize function
     * @see \DB\SQL\Mapper::insert()
     */
    public function insert()
    {
        $this->_cleanFields();
        
        parent::insert();
    }
    
    /**
     * Call the sanitize function 
     * @see \DB\SQL\Mapper::update()
     */
    public function update()
    {
        $this->_cleanFields();
        
        parent::update();
    }
    
    private function _cleanFields()
    {
        foreach ($this->fields as $key => $value)
        {
            $cleanValue = Controller::sanitizeDatas($value['value']);
            
            // Doing uppercase
            if (in_array($key, array('ville', 'b_ville', 'pays')))
            {
                $this->fields[$key]['value'] = Controller::utf8_strtoupper($cleanValue);
            }
            
            // Doing lowercase
            if (in_array($key, array('email')))
            {
                $this->fields[$key]['value'] = Controller::utf8_strtolower($cleanValue);
            }
            
            // Cleaing tel phone
            if (in_array($key, array('fixe', 'mobile')))
            {
                require_once(dirname(__FILE__)."/../../../_lib/libphonenumber-for-PHP-master/PhoneNumberUtil.php");
                
                $phoneUtil = \com\google\i18n\phonenumbers\PhoneNumberUtil::getInstance();
                if (!empty($cleanValue))
                {
                    $telProto = $phoneUtil->parseAndKeepRawInput($cleanValue, "FR");
                
                    if ($phoneUtil->isValidNumber($telProto))
                    {
                        $this->fields[$key]['value'] =  $phoneUtil->format($telProto, \com\google\i18n\phonenumbers\PhoneNumberFormat::NATIONAL);
                    }
                }
            }
            
            // Doing NULL sql
            if (empty($cleanValue))
            {
                $cleanValue = null;
            }
            
            $this->fields[$key]['value'] = $cleanValue;
        }
    }
    
    /**
     * Generic function to get the SQL array regexp for get the requested values
     * @param unknown $value
     * @return string
     */
    public static function getArraySqlRegexp($value)
    {
        return '('.MyMapper::SQL_ARRAY_DELIMITER.'|[^0-9]*)('.$value.')('.MyMapper::SQL_ARRAY_DELIMITER.'|[^0-9]*)';
    }
    
    

    /**
     * Generic function to get the SQL array field
     * @return string
     */
    public static function getArraySqlField($field)
    {
        return MyMapper::SQL_ARRAY_FIELD_PREFIX.$field.' REGEXP ?';
    }
    
    protected function cleanDataArraySqlToPHP($data)
    {
        //$data = $this->db->log();
        //echo '<pre>'.print_r ($result, true).'</pre>';
        $sqlArrayFieldPrefixLength = strlen(MyMapper::SQL_ARRAY_FIELD_PREFIX);
        foreach($data as $iRow => $guestData)
        {
        // Check if sql field are array
            foreach ($guestData as $key => $currentGuestData)
            {
                if (substr($key, 0, $sqlArrayFieldPrefixLength) == MyMapper::SQL_ARRAY_FIELD_PREFIX)
                {
                    $newKey = substr($key, $sqlArrayFieldPrefixLength);
                 
                    // The field is an array
                    $data[$iRow][$newKey] = explode(MyMapper::SQL_ARRAY_DELIMITER, $currentGuestData);
                     
                    // Delete the raw result
                    unset($data[$iRow][$key]);
                }
            }
            	
            //
            $data[$iRow]['invitationids'] = implode(',', $data[$iRow]['invitationid']);
		}
		
		return $data;
    }
    
    public static function getEmptyStatsArray($eid = null)
    {
        $emptyStatsArray = [
            'nbGuestsAcc' => 0,
            'nbGuestsAccPresenceYes' => 0,
            'nbGuestsAnswerNo' => 0,
            'nbGuestsAnswerNone' => 0,
            'nbGuestsAnswerYes' => 0,
            'nbGuestsAnswerNoPercent' => 0,
            'nbGuestsAnswerNonePercent' => 0,
            'nbGuestsAnswerYesPercent' => 0,
            'nbGuestsPresenceNo' => 0,
            'nbGuestsPresenceNone' => 0,
            'nbGuestsPresenceYes' => 0,
            'nbGuestsPresenceNoPercent' => 0,
            'nbGuestsPresenceNonePercent' => 0,
            'nbGuestsPresenceYesPercent' => 0,
            'nbGuestsTotal' => 0,
            'nbInvValidated' => 0,
            'nbInvNotValidated' => 0,
            'nbInvitations' => 0,
            'nbInvSent' => 0,
            'nbInvNotSent' => 0,
            'nbHostsTotal' => 0,
        ];
        
        if ($eid !== null && intval($eid) > 0)
        {
            $emptyStatsArray = array_merge(['eid' => (int) $eid], $emptyStatsArray);
        }
        
        return $emptyStatsArray;
    }
    
    public static function addStatsArray($data)
    {
        if ($data->nbGuestsTotal > 0)
        {
            // Add percent stats
            $data->nbGuestsAnswerYesPercent = round(($data->nbGuestsAnswerYes * 100) / $data->nbGuestsTotal, static::FLOAT_PRECISION);
            $data->nbGuestsAnswerNoPercent = round(($data->nbGuestsAnswerNo * 100) / $data->nbGuestsTotal, static::FLOAT_PRECISION);
            $data->nbGuestsAnswerNonePercent = round(($data->nbGuestsAnswerNone * 100) / $data->nbGuestsTotal, static::FLOAT_PRECISION);
    
            $data->nbGuestsPresenceYesPercent = round(($data->nbGuestsPresenceYes * 100) / $data->nbGuestsTotal, static::FLOAT_PRECISION);
            $data->nbGuestsPresenceNoPercent = round(($data->nbGuestsPresenceNo * 100) / $data->nbGuestsTotal, static::FLOAT_PRECISION);
            $data->nbGuestsPresenceNonePercent = round(($data->nbGuestsPresenceNone * 100) / $data->nbGuestsTotal, static::FLOAT_PRECISION);
        }
        else
        {
            $data->nbGuestsAnswerYesPercent = 0;
            $data->nbGuestsAnswerNoPercent = 0;
            $data->nbGuestsAnswerNonePercent = 0;
            	
            $data->nbGuestsPresenceYesPercent = 0;
            $data->nbGuestsPresenceNoPercent = 0;
            $data->nbGuestsPresenceNonePercent = 0;
        }
        
        return $data;
    }
    
    /**
     * Check if the given user already exists in the database
     * @return bool true or false
     */
    public static function userExists($data)
    {
        $result = static::getUserData($data);
        if ($result !== false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public static function getUserData($data)
    {
        $sql = "SELECT uid, email FROM users AS u WHERE (u.email = :email AND u.email IS NOT NULL) OR (u.hash = :hash)";
        $value = [':email' => (isset($data['email']) ? $data['email'] : null), ':hash' => static::getUserHash($data)];
         
        $result = static::getDbInstance()->exec($sql, $value);
        return (count($result) === 0 ? null : (object) ['email' => (empty($result[0]['email']) ? null : $result[0]['email']), 'uid' => (int) $result[0]['uid']]);
    }
    
    /**
     * Insert a new user if does not exists
     * if new isert is done, it done in 3 tables
     * 
     * @param int $hostId
     * @param array $data
     * @return int userID
     */
    public static function saveUserData($hostId, $data)
    {
        $userId = false;
        
        // Check if the user exists
        $userData = MyMapper::getUserData($data);
        if($userData === null)
        {
            // User DOES NOT exists
            
            // INSERT an user entry
            $user = new Users(static::getDbInstance());
            $user->email = (isset($data['email']) ? $data['email'] : null);
            $user->password = Encrypt::load()->proceed(static::getF3Instance()->get('db_pass'));
            $user->level = 3;
            $user->hash = static::getUserHash($data);
            $user->creatorUid = $hostId;
            $user->save();
            $res = static::getDbInstance()->exec("SELECT LAST_INSERT_ID() as uid");
            
            // Fresh userID
            $userId = (int) $res[0]['uid'];
            
            // INSERT an userprofile entry
            $profile = new UserProfile(static::getDbInstance());
            $profile->userID = $userId;
            $profile->civilite = (isset($data['civilite']) ? $data['civilite'] : null);
            $profile->nom = (isset($data['nom']) ? $data['nom'] : null);
            $profile->prenom = (isset($data['prenom']) ? $data['prenom'] : null);
            $profile->save();
            
            //INSERT an userjobinfo entry
            $job = new UserJobinfos(static::getDbInstance());
            $job->userID = $userId;
            $job->adresse = (isset($data['adresse']) ? $data['adresse'] : null);
            $job->ville = (isset($data['ville']) ? $data['ville'] : null);
            $job->cp = (isset($data['cp']) ? $data['cp'] : null);
            $job->pays = (isset($data['pays']) ? $data['pays'] : null);
            $job->portable = (isset($data['portable']) ? $data['portable'] : null);
            $job->fixe = (isset($data['fixe']) ? $data['fixe'] : null);
            $job->fonction = (isset($data['fonction']) ? $data['fonction'] : null);
            $job->societe = (isset($data['societe']) ? $data['societe'] : null);
            $job->save();
        }
        else
        {
            // User EXISTS
            
            if(!$userData->email && !empty($data['email']))
            {
                // User EXISTS, update email if current user does not have and editor set email
                $user = new Users(static::getDbInstance());
                $user->load(array("uid = :uid", array(':uid' => $userData->uid)));
                $user->email = $data['email'];
                $user->save();
            }
            
            // userID
            $userId = $userData->uid;
        }
        
        return $userId;
    }
    
    /**
     * 
     * @param string | array $nom
     * @param string $prenom
     * @param string $societe
     * @return string hash in 40 carac
     */
    public static function getUserHash($nom, $prenom = null, $societe = null)
    {
        // Data are the raw array 
        if (is_array($nom) && $prenom === null && $societe === null)
        {
            $tmpData = $nom;
            $nom = $tmpData['nom'];
            $prenom = $tmpData['prenom'];
            $societe = $tmpData['societe'];
               unset($tmpData);            
        }
        
        $data = [];
        $data[] = Controller::utf8_strtolower(Controller::sanitizeDatas($nom, true));
        $data[] = Controller::utf8_strtolower(Controller::sanitizeDatas($prenom, true));
        $data[] = Controller::utf8_strtolower(Controller::sanitizeDatas($societe, true));
        
        $hash = implode(static::SQL_ARRAY_DELIMITER, $data);
        $hash = sha1($hash);
        
        //echo "<pre>"; print_r($data); echo "</pre>"; echo $hash."<br><br>"; die("[".__LINE__."] ".__FILE__);
        
        return $hash;
    } 
    
    /**
     * Fix invitationguests table
     * 
     * When a guest is validated, has answer or will be present, all the field need to be re set after add a new user already invited, validted, ...
     * @param int $eventID
     * @param int $guestID
     */
    public static function fixInvitationGuests($eventID, $guestID)
    {
        $value = array(':eventID' => (int) $eventID, ':guestID' => (int) $guestID);
        
        
        // guestAnswer and guestPresence in invitationguests table
        $sql = "SELECT sum(ig.guestAnswer) AS guestAnswerCount, 
            count(ig.guestAnswer) AS guestAnswerTot, 
            sum(ig.guestPresence) AS guestPresenceCount, 
            count(ig.guestPresence) AS guestPresenceTot 
            FROM invitationguests AS ig 
            LEFT JOIN invitations AS i ON (i.iid = ig.invitationID) 
            WHERE i.eventID = :eventID 
            AND i.guestID = :guestID";
        $result = static::getDbInstance()->exec($sql, $value);
        if (!empty($result))
        {
            $sqls = [];
            
            $genSql = function($type) use ($result, $sqls)
            {
                if ($result[0][$type.'Count'] > 0 && $result[0][$type.'Count'] < $result[0][$type.'Tot'])
                {
                    // UPDATE anwser
                    return "UPDATE invitationguests AS ig SET ig.".$type." = 1 
                        WHERE ig.".$type." = 0 
                            AND ig.invitationID IN (SELECT i.iid FROM invitations AS i WHERE i.eventID = :eventID AND i.guestID = :guestID)";
                }
            };
            
            foreach (['guestAnswer', 'guestPresence'] as $currentType)
            {
                $sql = $genSql($currentType);
                if (!empty($sql))
                {
                    $sqls[] = $sql;
                }
            }
            
            foreach ($sqls as $sql)
            {
                // Update rows
                $updatedRow = static::getDbInstance()->exec($sql, $value);
            }
        }
        
        // validated, invitationSent and invitationSentType in invitations table
        $sql = "SELECT sum(i.validated) AS validatedCount, 
            count(i.validated) AS validatedTot, 
            sum(i.invitationSent) AS invitationSentCount, 
            count(i.invitationSent) AS invitationSentTot,
            sum(i.invitationSentType) AS invitationSentTypeCount, 
            count(i.invitationSentType) AS invitationSentTypeTot             
            FROM invitations AS i 
            WHERE i.eventID = :eventID 
            AND i.guestID = :guestID";
        $result = static::getDbInstance()->exec($sql, $value);
        if (!empty($result))
        {
            $sqls = [];
        
            $genSql = function($type) use ($result, $sqls)
            {
                if ($result[0][$type.'Count'] > 0 && $result[0][$type.'Count'] < $result[0][$type.'Tot'])
                {
                    // UPDATE anwser
                    return "UPDATE invitations AS i SET i.".$type." = 1 WHERE i.".$type." = 0 AND i.eventID = :eventID AND i.guestID = :guestID";
                }
            };
        
            foreach (['validated', 'invitationSent', 'invitationSentType'] as $currentType)
            {
                $sql = $genSql($currentType);
                if (!empty($sql))
                {
                    $sqls[] = $sql;
                }
            }
        
            foreach ($sqls as $sql)
            {
                // Update rows
                $updatedRow = static::getDbInstance()->exec($sql, $value);
            }
            
            //echo "<pre>"; print_r($GLOBALS); echo "</pre><br>\n"; echo "Location: [<b>".__LINE__."</b>] <b>".__FILE__."</b><br>\n"; die('ici');
        }
    }
    
    /**
     * Get the DataBase instance
     */
    public static function getDbInstance()
    {
        if (static::$_db === null)
        {
            $f3 = static::getF3Instance();
            
            $db=new DB\SQL(
                $f3->get('db_dns') . $f3->get('db_name'),
                $f3->get('db_user'),
                $f3->get('db_pass')
            );
            
            static::$_db = $db;
        }
        
        return static::$_db;
    }
    
    /**
     * Get the F3 instance
     */
    public static function getF3Instance() 
    {
        if (static::$_f3 === null)
        {
            static::$_f3 = Base::instance();
        }
        
        return static::$_f3;        
    }
}
