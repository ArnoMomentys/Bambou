<?php

/**
 * Base controller
 */
class Controller {

    protected $f3;
    protected $db;
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

    static public function sanitizeDatas($data, $removeAccent = false)
    {
		require_once(dirname(__FILE__)."/../../../_lib/forceutf8-master/src/ForceUTF8/Encoding.php");
		//require_once(dirname(__FILE__)."/../../../_lib/utf8/utf8.php");
		
		$data = trim($data);
		$data = \ForceUTF8\Encoding::toUTF8($data);
		
		if ($removeAccent)
		{
			$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
			$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
			$data = str_replace($a, $b, $data);
		}
		
        $data = preg_replace('/^.*?;/i', ';', $data);
        $data = str_replace('"', '', $data);
        $data = str_replace("'", "", $data);
        $find[] = 'â€œ';  // left side double smart quote
        $find[] = 'â€';  // right side double smart quote
        $find[] = 'â€˜';  // left side single smart quote
        $find[] = "\n";  // left side single smart quote
        $find[] = "\r";  // left side single smart quote
        $find[] = "\n\r";  // left side single smart quote
        $find[] = "\r\n";  // left side single smart quote
        $find[] = 'â€™';  // right side single smart quote
        $replace[] = '';
        $replace[] = '';
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        return str_replace($find, $replace, $data);
    }


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