<?php

namespace Api;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author tomi_weber
 */
class Auth {
	
	/**
	 * 
	 * @param string $requestedHeader
	 */
	public function check($email, $hash) {
		
		$db = new \Db\DB();
		$row = $db->subQuery("SELECT * FROM pushmail_auth WHERE email = '".$email."' AND hash = '".$hash."'", "fetch");
		
		if(is_array($row) && count($row) > 0) { 
			return true;
		} else {
			return false;
		}
		
	}
}
