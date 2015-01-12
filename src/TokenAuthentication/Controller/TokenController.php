<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/TokenAuthentication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TokenAuthentication\Controller;

use ZendServer\Mvc\Controller\ActionController as AbstractActionController;
use TokenAuthentication\Mapper\Token;
use Zend\Validator\Regex;
use TokenAuthentication\Exception;
use Zend\Authentication\AuthenticationService;
use TokenAuthentication\Authentication\Adapter\TokenAdapter;
use TokenAuthentication;
use ZendServer\Log\Log;

class TokenController extends AbstractActionController {
	
    public function indexAction() {
    	$params = $this->getParameters(array('hash' => ''));
    	$hash = $this->validateToken($params['hash']);
    	/// create a session and redirect to target
    	$authService = $this->getLocator()->get('Zend\Authentication\AuthenticationService'); /* @var $authService AuthenticationService */
    	$tokenAdapter = $this->getLocator()->get('TokenAdapter'); /* @var $tokenAdapter TokenAdapter */
    	$tokenAdapter->setToken($hash);
    	$result = $authService->authenticate($tokenAdapter);
    	
    	if ($result->isValid()) {
    		$result->getIdentity()->setLoggedIn();
	    	$this->redirect()->toRoute('home');
    	} else {
    		Log::warn('Token authentication failed, redirect to login page');
    		$this->redirect()->toRoute('login');
    	}
        return $this->getResponse();
    }
    /**
     * @param string $token
     * @throws Exception
     * @return string
     */
    private function validateToken($token) {
    	$validator = new Regex('#^[[:alnum:]]{64}$#');
    	if ($validator->isValid($token)) {
    		return $token;
    	}
    	throw new Exception('Token parameter is invalid');
    }
}
