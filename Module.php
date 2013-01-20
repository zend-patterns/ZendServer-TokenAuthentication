<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/TokenAuthentication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TokenAuthentication;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use TokenAuthentication\Mapper\Token;

class Module implements AutoloaderProviderInterface, ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig() {
    	return array(
    				'factories' => array(
    					'TokenAuthentication\Mapper\Token' => function($sm){
	    					$mapper = new Token();
	    					$mapper->setTableGateway($sm->get('guiToken_tg'));
	    					$mapper->setWebapiMapper($sm->get('WebAPI\Db\Mapper'));
	    					return $mapper;
	    				}
    				)
    			);
    }
    
}