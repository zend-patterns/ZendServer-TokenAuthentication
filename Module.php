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
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventManager;
use Zend\Mvc\Router\Http\RouteMatch;
use ZendServer\Log\Log;
use TokenAuthentication\Authentication\Adapter\TokenAdapter;
use Zend\Db\TableGateway\TableGateway;
use Application\Db\Connector;

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
    				'aliases' => array(
    					'TokenAdapter' => 'TokenAuthentication\Authentication\Adapter\TokenAdapter'
    				),
    				'factories' => array(
    					'TokenAuthentication\Mapper\Token' => function($sm){
	    					$mapper = new Token();
	    					$mapper->setTableGateway(new TableGateway('GUI_USERS_TOKEN', $sm->get(Connector::DB_CONTEXT_GUI)));
	    					$mapper->setWebapiMapper($sm->get('WebAPI\Db\Mapper'));
	    					return $mapper;
	    				},
	    				'TokenAuthentication\Authentication\Adapter\TokenAdapter' => function ($sm) {
	    					$adapter = new TokenAdapter();
	    					$adapter->setTokenMapper($sm->get('TokenAuthentication\Mapper\Token'));
	    					$adapter->setUsersMapper($sm->get('Users\Db\Mapper'));
	    					return $adapter;
	    				}
    				)
    			);
    }

    public function onBootstrap(MvcEvent $e) {
    	$events = $e->getApplication()->getEventManager(); /* @var $events EventManager */
    	$events->attach(MvcEvent::EVENT_ROUTE, array($this, 'overrideSessionControl'),-800);
    }
    
    public function overrideSessionControl(MvcEvent $e) {
    	Log::debug(__METHOD__);
    	$routeMatch = $e->getRouteMatch(); /* @var $routeMatch RouteMatch */
    	if ($routeMatch->getParam('controller') == 'Token') {
    		Log::info('Session control override: Token access');
    		$e->setParam('useSessionControl', false);
    	}
    }
}