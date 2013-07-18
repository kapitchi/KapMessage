<?php
return array(
    'plugin_manager' => array(
        'invokables' => array(
            //TODO disabled until it's fully implemented in showcase
            //'Messenger/KapitchiIdentity' => 'KapMessage\Plugin\KapitchiIdentity',
        ),
    ),
    'controller_plugins' => array(
        'classes' => array(
            //'test' => 'Test\Controller\Plugin\Test'
        ),
    ),
    'router' => array(
        'routes' => array(
            'messenger' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/messenger',
                    'defaults' => array(
                        '__NAMESPACE__' => 'KapMessage\Controller',
                    ),
                ),
                'may_terminate' => false,
                'child_routes' => array(
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
                    'message' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/message[/:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Message',
                            ),
                        ),
                    ),
                    'inbox' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/inbox[/:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Inbox',
                            ),
                        ),
                    ),
                    
                    'api' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/api',
                            'defaults' => array(
                                '__NAMESPACE__' => 'KapMessage\Controller\Api',
                            ),
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'message' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/message[/:id][/:action]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'Message',
                                    ),
                                ),
                            ),
                            'delivery' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/delivery[/:id][/:action]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'Delivery',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
