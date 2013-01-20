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

class TokenController extends AbstractActionController {
	
    public function indexAction() {
    	/// retrieve the token parameter
    	$tokenMapper = $this->getLocator()->get('TokenAuthentication\Mapper\Token');
    	/// authenticate the token
    	
    	/// create a session and redirect to target
    	$this->redirect()->toRoute('home');
        return $this->getResponse();
    }
}
