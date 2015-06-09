<?php

namespace Api;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DKIM
 *
 * @author tomi_weber
 */
class DKIM {
	
	/**
     * DKIM selector.
     * @type string
     */
    public $DKIM_selector = '';

    /**
     * DKIM Identity.
     * Usually the email address used as the source of the email
     * @type string
     */
    public $DKIM_identity = '';

    /**
     * DKIM passphrase.
     * Used if your key is encrypted.
     * @type string
     */
    public $DKIM_passphrase = '';

    /**
     * DKIM signing domain name.
     * @example 'example.com'
     * @type string
     */
    public $DKIM_domain = '';

    /**
     * DKIM private key file path.
     * @type string
     */
    public $DKIM_private = '';
	
	/**
	 * Get DKIM elements from database
	 * 
	 * @param type $email
	 */
	public function getElement($email) {
		
		$db = new \Db\DB();
		$row = $db->subQuery("SELECT * FROM pushmail_dkim WHERE email = '".$email."'", "fetch");
		
		if(is_array($row)) {
			$this->DKIM_identity = $email;
			$this->DKIM_domain = $row['domain'];
			$this->DKIM_private = $row['private'];
			$this->DKIM_selector = $row['selector'];
		}
		
	}
}
