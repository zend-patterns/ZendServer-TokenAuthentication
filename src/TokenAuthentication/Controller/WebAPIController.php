<?php

namespace TokenAuthentication\Controller;

use ZendServer\Mvc\Controller\WebAPIActionController;
use Users;
use Zend\Crypt\Hmac;
use Zend\Math\Rand;

class WebAPIController extends WebAPIActionController {
	
	public function tokenGenerateAction() {
		$tokenMapper = $this->getLocator()->get('TokenAuthentication\Mapper\Token');
		$token = $tokenMapper->generateTokenByIdentity();
		return array('token' => $token);
	}
	
}