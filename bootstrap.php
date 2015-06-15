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

	if(!key($app->request->{REQEST_METHOD}()) != 0) {
		array_push($requestData, $app->request->{REQEST_METHOD}());
	} else {
		$requestData = $app->request->{REQEST_METHOD}();
		if(count($requestData) > 25) { 
			echo json_encode(array('error' => 'Limit extended'));
		}
	}
	
	$mailer = new \Api\MailGenerator();
	
	foreach($requestData as $key => $oneRequestData) {
		$mailer->getRequest($oneRequestData);

		$objAuth = new \Api\Auth();

		if($objAuth->check($oneRequestData['from'], $app->request->headers->get('SECRET_HASH'))) {
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