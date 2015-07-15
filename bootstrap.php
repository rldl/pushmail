<?php

if (empty($_ENV['SLIM_MODE'])) {
    $_ENV['SLIM_MODE'] = (getenv('SLIM_MODE'))
        ? getenv('SLIM_MODE') : 'development';
}
 
$config = array();
 
$configFile = dirname(__FILE__) . '/share/config/'
    . $_ENV['SLIM_MODE'] . '.php';
 
if (is_readable($configFile)) {
    require_once $configFile;
} else {
    require_once dirname(__FILE__) . '/share/config/default.php';
}
 
// Routing
$app = new \Slim\Slim(array(
    'debug' => true
));
$app->{REQEST_METHOD}('/api/send', function() use ($app) {

	$requestData = array();
	$responseData = array();
	if($app->request->{REQEST_METHOD}('debug') == 1) {
		var_dump($app->request->{REQEST_METHOD}());
	}
	if(is_string(key($app->request->{REQEST_METHOD}('msg')))) {
		array_push($requestData, $app->request->{REQEST_METHOD}('msg'));
	} else {
		$requestData = $app->request->{REQEST_METHOD}('msg');
		if(count($requestData) > 25) { 
			echo json_encode(array('error' => 'Limit extended'));
		}
	}
	
	$mailer = new \Api\MailGenerator();
	
	foreach($requestData as $key => $oneRequestData) {
		$mailer->getRequest($oneRequestData);
		
		$objAuth = new \Api\Auth();

		if($objAuth->check($mailer->getFrom(), $app->request->headers->get('Secret-Hash'))) {
			$res = $mailer->generateMail();
			if($res) {
				array_push($responseData, array($key => array('ok' => 1)));
			} else {
				array_push($responseData, array($key => array('error' => 1)));
			}
		} else {
			array_push($responseData, array($key => array('error' => 1)));
		}
	
	}
	
	echo json_encode($responseData);
		
});

$app->run();
