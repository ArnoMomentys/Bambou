<?php

/**
 * Instanciating F3 lib
 * @var object
 */
$f3=require('../_lib/base.php');

/**
 * Config and Routing files
 */
$f3->config('../_conf/config.ini');
$f3->config('../_conf/routes.ini');
$f3->config('../_conf/dict.ini');
$f3->config('../_conf/errors.ini');

/**
 * Set error template
 */
$f3->set('ONERROR',
	function($f3){
		echo 'ERREUR '.$f3->get( 'ERROR.code' ).'<br>';
		$dict = new Controller();
		echo $dict->T($f3->get( 'ERROR.status' ), true).'<br>';
		echo $f3->get( 'ERROR.text' ).'<br><br><br>';
		if($f3->get('DEBUG') === 3) {
			echo '<pre>';
			print_r($f3->get('ERROR.trace'));
			echo '</pre>';
		}
		// echo \Template::instance()->render('error.htm');
	}
);

/**
 * Load F3 instance
 */
$f3->run();