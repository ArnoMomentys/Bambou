<?php

/**
 * Base controller
 */
class Controller {

    protected $f3;
    protected $db;
    const SQL_ARRAY_DELIMITER = '[__¤~~¤__]';
    const SQL_ARRAY_FIELD_PREFIX = '__array__';
    private static $geoip2 = null;
    private $errors = array();
    

 	/**
 	 * Actions to perform before routing
 	 * @return [type] [description]
 	 */
    function beforeroute() {
	}


 	/**
 	 * Actions to perform once routing is ok
 	 *
 	 * render the layout
 	 * remove session flash messages
 	 *
 	 * @return string Rendered template
 	 */
    function afterroute() {
    	echo Template::instance()->render('layout.htm');
		$this->f3->clear('SESSION.msg');
	   	$this->f3->clear('SESSION.errors');
	   	$this->f3->clear('SESSION.warnings');
    }


 	/**
 	 * Instanciate a default connexion to database
 	 * And an instance of F3
 	 */
    function __construct() {

        $f3=Base::instance();

        $db=new DB\SQL(
            $f3->get('db_dns') . $f3->get('db_name'),
            $f3->get('db_user'),
            $f3->get('db_pass')
        );

    	$this->f3=$f3;
    	$this->db=$db;
    }


    /**
     * T translation helper function
     * @param  string key the key term to translate
     * @return  string the corresponding term translation depending on lang param
     */
    public function T() {
    	$sArg = func_get_arg(0);
    	$aTerm = $this->f3->get( $sArg );
    	$l = $this->f3->get('SESSION.lang') ? $this->f3->get('SESSION.lang') : $this->f3->get('LANGUAGE');
    	if( !empty( $aTerm ) ) {
    		return $aTerm[$l];
    	} else {
    		return "!! " . $sArg . " !!";
    	}
    }

    static public function toLatin1($data) {
		require_once(dirname(__FILE__)."/../../../_lib/forceutf8-master/src/ForceUTF8/Encoding.php");
    	return \ForceUTF8\Encoding::toLatin1($data);
    }
    
    static public function utf8_strtoupper($data)
    {
        $data = mb_strtoupper($data, 'UTF-8');
        
        return $data;
    }
    
    static public function utf8_strtolower($data)
    {
        $data = mb_strtolower($data, 'UTF-8');
        
        return $data;
    }
    
    static public function sanitizeDatas($data, $removeAccent = false)
    {
		require_once(dirname(__FILE__)."/../../../_lib/forceutf8-master/src/ForceUTF8/Encoding.php");
		
		// Trim input data
		$data = trim($data);
		
		// Convert to latin1 String (for convert ms special carac)
		$data = \ForceUTF8\Encoding::toLatin1($data);
		
		// Convert MS Word special carac
		$a = array(chr(130), chr(131), chr(132), chr(133), chr(134), chr(135), chr(136), chr(137), chr(138), chr(139), chr(140), chr(145), chr(146), chr(147), chr(148), chr(149), chr(150), chr(151), chr(152), chr(153), chr(154), chr(155), chr(156), chr(159));
		$b = array(',', 'NLG', '"', '...', '**', '***', '^', 'o/oo', 'Sh', '<', 'OE', "'", "'", '"', '"', '-', '-', '--', '~', '(TM)', 'sh', '>', 'oe', 'Y');
		$data = str_replace($a, $b, $data);
		
		// If wanted, remove accent
		if ($removeAccent)
		{
			$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
			$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
			$data = str_replace($a, $b, $data);
		}
		
		// Encode data to UTF8
		$data = \ForceUTF8\Encoding::toUTF8($data);
		
		// Convert all HTML special chars to UTF-8
		$data = html_entity_decode($data, ENT_QUOTES, "utf-8");
		
		return $data;
    }

    /**
     * Format date to human reading (including langage)
     * @param unknown $dateToDisplay
     * @return string
     */
    public static function displayDate($dateToDisplay)
    {
        $lang = Base::instance()->get('SESSION.lang', 'fr');
        
        switch ($lang)
        {
            case 'en':
                $pattern = "%A %d %B %Y";
                break;
                
            case 'fr':
            default :   
                $pattern = "%A %d %B %Y";
                break;
        }
        
        $date = strftime($pattern, strtotime($dateToDisplay));
       
        $date = utf8_encode($date);
        
        return $date;
    }
    
    /**
     * cpToRegion : give the region name of given CP
     * @param  string cp the CP that we want the region
     * @return  string the region
     */
    public static function cpToRegion($cp)
    {
    	return null;
    	
    	if (strlen($cp) < 2)
    	{
    		return null;
    	}
    
    	$region = array(
    			'75' => 'Ile-de-France',
    			'77' => 'Ile-de-France',
    			'78' => 'Ile-de-France',
    			'91' => 'Ile-de-France',
    			'92' => 'Ile-de-France',
    			'93' => 'Ile-de-France',
    			'94' => 'Ile-de-France',
    			'95' => 'Ile-de-France',
    			'51' => 'Champagne-Ardenne',
    			'08' => 'Champagne-Ardenne',
    			'10' => 'Champagne-Ardenne',
    			'52' => 'Champagne-Ardenne',
    			'80' => 'Picardie',
    			'02' => 'Picardie',
    			'60' => 'Picardie',
    			'76' => 'Haute-Normandie',
    			'27' => 'Haute-Normandie',
    			'45' => 'Centre',
    			'18' => 'Centre',
    			'28' => 'Centre',
    			'36' => 'Centre',
    			'37' => 'Centre',
    			'41' => 'Centre',
    			'14' => 'Basse-Normandie',
    			'50' => 'Basse-Normandie',
    			'61' => 'Basse-Normandie',
    			'21' => 'Bourgogne',
    			'58' => 'Bourgogne',
    			'71' => 'Bourgogne',
    			'89' => 'Bourgogne',
    			'59' => 'Nord-Pas-de-Calais',
    			'62' => 'Nord-Pas-de-Calais',
    			'57' => 'Lorraine',
    			'54' => 'Lorraine',
    			'55' => 'Lorraine',
    			'88' => 'Lorraine',
    			'67' => 'Alsace',
    			'68' => 'Alsace',
    			'25' => 'Franche-Comté',
    			'39' => 'Franche-Comté',
    			'70' => 'Franche-Comté',
    			'90' => 'Franche-Comté',
    			'44' => 'Pays-de-la-Loire',
    			'49' => 'Pays-de-la-Loire',
    			'53' => 'Pays-de-la-Loire',
    			'72' => 'Pays-de-la-Loire',
    			'85' => 'Pays-de-la-Loire',
    			'35' => 'Bretagne',
    			'22' => 'Bretagne',
    			'29' => 'Bretagne',
    			'56' => 'Bretagne',
    			'86' => 'Poitou-Charentes',
    			'16' => 'Poitou-Charentes',
    			'17' => 'Poitou-Charentes',
    			'79' => 'Poitou-Charentes',
    			'33' => 'Aquitaine',
    			'24' => 'Aquitaine',
    			'40' => 'Aquitaine',
    			'47' => 'Aquitaine',
    			'64' => 'Aquitaine',
    			'31' => 'Midi-Pyrénées',
    			'09' => 'Midi-Pyrénées',
    			'12' => 'Midi-Pyrénées',
    			'32' => 'Midi-Pyrénées',
    			'46' => 'Midi-Pyrénées',
    			'65' => 'Midi-Pyrénées',
    			'81' => 'Midi-Pyrénées',
    			'82' => 'Midi-Pyrénées',
    			'87' => 'Limousin',
    			'19' => 'Limousin',
    			'23' => 'Limousin',
    			'69' => 'Rhône-Alpes',
    			'01' => 'Rhône-Alpes',
    			'07' => 'Rhône-Alpes',
    			'26' => 'Rhône-Alpes',
    			'38' => 'Rhône-Alpes',
    			'42' => 'Rhône-Alpes',
    			'73' => 'Rhône-Alpes',
    			'74' => 'Rhône-Alpes',
    			'63' => 'Auvergne',
    			'03' => 'Auvergne',
    			'15' => 'Auvergne',
    			'43' => 'Auvergne',
    			'34' => 'Languedoc-Roussillon',
    			'11' => 'Languedoc-Roussillon',
    			'30' => 'Languedoc-Roussillon',
    			'48' => 'Languedoc-Roussillon',
    			'66' => 'Languedoc-Roussillon',
    			'13' => 'Provence-Alpes-Côte-d Azur',
    			'04' => 'Provence-Alpes-Côte-d Azur',
    			'05' => 'Provence-Alpes-Côte-d Azur',
    			'06' => 'Provence-Alpes-Côte-d Azur',
    			'83' => 'Provence-Alpes-Côte-d Azur',
    			'84' => 'Provence-Alpes-Côte-d Azur',
    			'20' => 'Corse',
    			'2A' => 'Corse',
    			'2B' => 'Corse',
    			'2a' => 'Corse',
    			'2b' => 'Corse'
    	);
    
    	$dept = substr($cp,0,2);
    
    	if (!isset($region[$dept]))
    	{
    		return null;
    	}
    
    	return $region[$dept];
    }
    
    /**
     * cpToDept : give the departement name of given CP
     * @param  string cp the CP that we want the departement
     * @return  string the departement
     */
    public static function cpToDept($cp)
    {
       	//$reader = static::geoip2()->city('Paris'); //($cp);
       	
    	return null;
    	
    	return $nom_dept[$dept];
    }

    
/*
    public static function geoip2()
    {
    	if(static::$geoip2 === null)
    	{
    		$geoip2BasePath = dirname(__FILE__).'/../../../_lib/geoip2/';
    		require_once($geoip2BasePath.'geoip2.phar');
    		
    		static::$geoip2 = new \GeoIp2\Database\Reader($geoip2BasePath.'GeoLite2-City.mmdb');
    	}
    	
    	return static::$geoip2;
    }
*/
    
    /**
     * setMessage
     * @param  string key the key term to translate
     * @return  string the corresponding term translation depending on lang param
     */
    public function setMessage($msg) {
    	$msg = htmlentities($msg);
    	$this->f3->set('SESSION.msg', $msg);
    }


    /**
     * collect errors from the process
     */
    public function errors($error)
    {
        if(is_array($error))
        {
            array_walk_recursive($error, function ($current) {
                $this->errors[count($this->errors)] = $current;
            });
        }
        else
        {
            $this->errors[count($this->errors)] = $error;
        }
        $this->f3->set('SESSION.errors', $this->errors);
    }


    public function UMS($init)
    {
        $secs = floor($init);
        $milli = (int) (($init - $secs) * 1000);

        $hours = ($secs / 3600);
        $minutes = (($secs / 60) % 60);
        $seconds = $secs % 60;
        if($minutes < 10)
        {
            $minutes = "0".$minutes;
        }

        if($seconds < 10)
        {
            $seconds = "0".$seconds;
        }

        $milli = /* code to ret the remaining milliseconds */
        $stageTime = "$minutes minutes $seconds secondes $milli millisecondes";
        return $stageTime;
    }


}