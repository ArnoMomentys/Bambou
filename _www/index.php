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

/**
 * Set error template
 */
$f3->set('ONERROR',
	function($f3){
		var_dump($f3->get('ERROR'));
		echo \Template::instance()->render('error.htm');
	}
);

/**
 * Load F3 instance
 */
$f3->run();