<?php 


class Encrypt  {


	protected $UKEY = "E4HD9h4DhS23DYfhHemkS3Nf";
	protected $UIV = "fYfhHeDm";
	protected $UBIT_CHECK = 8;
	protected static $instance;


    protected function __construct() {}
    private function __clone() {}
    private function __wakeup() {}


    public static function load() { 
    	if(!isset(self::$instance)) { 
    		self::$instance = new self;
    	}
    	return self::$instance;
    }


    /* No comment */
    public function proceed($text) {
    	
    	$text_num = str_split($text, $this->UBIT_CHECK);
    	$text_num = $this->UBIT_CHECK - strlen( $text_num[count($text_num)-1] );
    	for ($i=0;$i<$text_num; $i++) { $text = $text . chr($text_num); }
    		$cipher = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
    	mcrypt_generic_init($cipher, $this->UKEY, $this->UIV);
    	$decrypted = mcrypt_generic($cipher, $text);
    	mcrypt_generic_deinit($cipher);

    	return base64_encode($decrypted);
    }


    /* No comment */
    public function invert($encrypted_text){
    	
    	$cipher = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
    	mcrypt_generic_init($cipher, $this->UKEY, $this->UIV);
    	$decrypted = mdecrypt_generic($cipher, base64_decode($encrypted_text));
    	mcrypt_generic_deinit($cipher);
    	$last_char=substr($decrypted, -1);
    	for($i=0; $i<$this->UBIT_CHECK-1; $i++){
    		if(chr($i) == $last_char){
    			$decrypted = substr($decrypted, 0, strlen($decrypted)-$i);
    			break;
    		}
    	}

    	return $decrypted;
    }

}
