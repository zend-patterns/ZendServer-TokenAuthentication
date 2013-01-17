<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Token' => 'TokenAuthentication\Controller\TokenController',
            'TokenWebAPI-1_3' => 'TokenAuthentication\Controller\WebAPIController',
        ),
    ),
        'webapi_routes' => array(
        	'tokenGenerate' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/Api/tokenGenerate',
                    'defaults' => array(
                        'controller'    => 'TokenWebAPI',
                        'action'        => 'tokenGenerate',
                    	'versions'		=> array('1.3')
                    ),
                ),
                'may_terminate' => true,
        	),
        ),
    'router' => array(
        'routes' => array(
            'token-authentication' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/token',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'TokenAuthentication\Controller',
                        'controller'    => 'Token',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'TokenAuthentication' => __DIR__ . '/../view',
        ),
    ),
);
