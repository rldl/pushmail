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

	$mailer = new \Api\MailGenerator();
	$mailer->getRequest($app->request->{REQEST_METHOD}());

	$objAuth = new \Api\Auth();
	
	if($objAuth->check($app->request->{REQEST_METHOD}('from'), $app->request->headers->get('SECRET_HASH'))) {
		$res = $mailer->generateMail();
		if($res) {
			echo json_encode(array('ok' => 1));
		} else {
			echo json_encode(array('error' => 1));
		}
	} else {
		echo json_encode(array('error' => 1));
	}
		
});

$app->run();