<?php

namespace Api;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MailGenerator
 *
 * @author tomi_weber
 */
require '../lib/PHPMailer/PHPMailerAutoload.php';

class MailGenerator {

	private $from			= array();
	private $fromName		= array();
	private $to				= array();
	private $toName			= array();
	private $cc				= array();
	private $ccName			= array();
	private $bcc			= array();
	private $bccName		= array();
	private $replayTo		= array();
	private $replayToName	= array();
	private $html	= '';
	private $text	= '';
	private $title	= '';
	
	private $DKIM_domain;
	private $DKIM_key;
	private $DKIM_selector;
	
	private function _getAddress($data, $name, $secondName) {
		
		if(is_array($data[$name])) {
			foreach($data[$name] as $row) {
				if(is_object($row)) {
					array_push($this->$name, $row->address);
					array_push($this->$secondName, $row->name);
				} else {
					array_push($this->$name, $row);
				}
			}
		} else {
			if(is_object($data[$name])) {
				array_push($this->$name, $data[$name]->address);
				array_push($this->$secondName, $data[$name]->name);
			} else {
				array_push($this->$name, $data[$name]);
			}
		}
	}
	
	/**
	 * Method to recive POST data
	 * 
	 * @param array $requestArray
	 */
	public function getRequest($requestArray) {
		
		if(isset($requestArray['from'])) {
			$this->_getAddress($requestArray, 'from', 'fromName');
		}
		
		if(isset($requestArray['to'])) {
			$this->_getAddress($requestArray, 'to', 'toName');
		}
		
		if(isset($requestArray['cc'])) {
			$this->_getAddress($requestArray, 'cc', 'ccName');
		}
		
		if(isset($requestArray['bcc'])) {
			$this->_getAddress($requestArray, 'bcc', 'bccName');
		}
		
		if(isset($requestArray['replayTo'])) {
			$this->_getAddress($requestArray, 'replayTo', 'replayToName');
		}
		
		if(isset($requestArray['html'])) {
			$this->html = $requestArray['html'];
		}
		
		if(isset($requestArray['text'])) {
			$this->text = $requestArray['text'];
		}
		
		if(isset($requestArray['title'])) {
			$this->title = $requestArray['title'];
		}
		
	}

	/**
	 * Method to generate and send email
	 * 
	 * @return boolean
	 */
	public function generateMail() {
		
		$mail = new PHPMailer;

		$mail->From = $this->from[0];
		$mail->FromName = $this->fromName[0];
		foreach($this->to as $key => $row) {
			if(isset($this->toName[$key])) {
				$mail->addAddress($row, $this->toName[$key]);
			} else {
				$mail->addAddress($row);
			}
		}
		
		foreach($this->replayTo as $key => $row) {
			if(isset($this->replayToName[$key])) {
				$mail->addReplyTo($row, $this->replayToName[$key]);
			} else {
				$mail->addReplyTo($row);
			}
		}
		
		foreach($this->cc as $key => $row) {
			if(isset($this->ccName[$key])) {
				$mail->addCC($row, $this->ccName[$key]);
			} else {
				$mail->addCC($row);
			}
		}
		
		foreach($this->bcc as $key => $row) {
			if(isset($this->bccName[$key])) {
				$mail->addBCC($row, $this->bccName[$key]);
			} else {
				$mail->addBCC($row);
			}
		}
		
		$mail->isHTML(true);								  // Set email format to HTML

		$mail->Subject = $this->title;
		$mail->Body = $this->html;
		$mail->AltBody = $this->text;

		$objDKIM = new \Api\DKIM();
		$objDKIM->getElement($mail->From);
		
		$mail->DKIM_domain = $objDKIM->DKIM_domain;
		$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'].'share/'.$objDKIM->DKIM_private;
		$mail->DKIM_selector = $objDKIM->DKIM_selector;
		$mail->DKIM_passphrase = $objDKIM->DKIM_passphrase;
		$mail->DKIM_identifier = $objDKIM->DKIM_identity;
		
		if (!$mail->send()) {
			return $mail->ErrorInfo;
		} else {
			return true;
		}
	}

}
