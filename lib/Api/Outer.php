<?php

namespace Api;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Outer
 *
 * @author tomi_weber
 */

require '../lib/Curl/Curl.php';

class Outer {
	
	public $requestData = array();
	
	public function __construct($data) {
		$this->requestData = $data;
	}
	
	public function send() {
		
		$curl = new \Curl\Curl();
		$res = $curl->post("http://rd-at-2.cloudapp.net/api/send", $this->requestData);
	
		return $this->getRespose($curl, $res);
	}
	
	public function getRespose(\Curl\Curl $curl, $res) {
		if($res->http_error) {
			return array(
				'message' => $res->http_error_message,
				'code' => $res->http_status_code
			);
		} else {
			return $res;
		}
	}
}
