<?php

error_reporting(E_ERROR);

require dirname(__FILE__) . '/../../lib/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'lib/');

require_once( dirname(__FILE__) . '/../../lib/adodb/adodb-exceptions.inc.php');
require_once( dirname(__FILE__) . '/../../lib/adodb/adodb.inc.php' );

require dirname(__FILE__) . '/../../lib/Db/DB.php';

require dirname(__FILE__) . '/../../lib/Api/MailGenerator.php';
require dirname(__FILE__) . '/../../lib/Api/DKIM.php';
require dirname(__FILE__) . '/../../lib/Api/Auth.php';

define('REQEST_METHOD', 'get');

class MyConfig {

	//database
	static protected $dbPrefix			= ""; // "prefix_"
	static protected $dbHost				= "localhost";
	static protected $dbLogin			= "root";
	static protected $dbDatabase		= "push_mail";
	static protected $dbPass			= "";
	static protected $dbPort				= 3306;

	static public function getValue($string) {

		if (isset(self::$$string)) {
			return self::$$string;
		}else{
			return "Błąd konfiguracji dla zmiennej: ".$string." z klasy: ".get_class();
		}
	}
}