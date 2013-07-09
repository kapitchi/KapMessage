<?php

/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapMessage;

use Zend\EventManager\EventInterface,
    Zend\ModuleManager\Feature\ControllerProviderInterface,
    Zend\ModuleManager\Feature\ServiceProviderInterface,
    Zend\ModuleManager\Feature\ViewHelperProviderInterface,
	KapitchiBase\ModuleManager\AbstractModule,
    KapitchiEntity\Mapper\EntityDbAdapterMapper,
    KapitchiEntity\Mapper\EntityDbAdapterMapperOptions;

class Module extends AbstractModule
    implements ServiceProviderInterface, ControllerProviderInterface, ViewHelperProviderInterface
{

	public function onBootstrap(EventInterface $e) {
		parent::onBootstrap($e);
		
        $sm = $e->getApplication()->getServiceManager();
        
        //mz: WIP
//        $volume = $sm->get('KapMessage\FileManager\MessagePartFileVolume');
//        $volumeManager = $sm->get('KapitchiFileManager\Volume\Manager');
//        $volumeManager->setService('messagePart', $volume);
	}
    
    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'KapMessage\Controller\Message' => function($sm) {
                    $ins = new Controller\MessageController();
                    $ins->setEntityService($sm->getServiceLocator()->get('KapMessage\Service\Message'));
                    $ins->setEntityForm($sm->getServiceLocator()->get('KapMessage\Form\Message'));
                    return $ins;
                },
                'KapMessage\Controller\Inbox' => function($sm) {
                    $ins = new Controller\InboxController();
                    $ins->setMessageService($sm->getServiceLocator()->get('KapMessage\Service\Message'));
                    $ins->setDeliveryService($sm->getServiceLocator()->get('KapMessage\Service\Delivery'));
                    $ins->setAuthService($sm->getServiceLocator()->get('KapitchiIdentity\Service\Auth'));
                    return $ins;
                },
                //API
                'KapMessage\Controller\Api\Message' => function($sm) {
                    $ins = new Controller\Api\MessageRestfulController(
                        $sm->getServiceLocator()->get('KapMessage\Service\Message')
                    );
                    return $ins;
                },
                'KapMessage\Controller\Api\Delivery' => function($sm) {
                    $ins = new Controller\Api\DeliveryRestfulController(
                        $sm->getServiceLocator()->get('KapMessage\Service\Delivery')
                    );
                    return $ins;
                },
            )
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'messengerMessage' => function($sm) {
                    $ins = new View\Helper\Message($sm->getServiceLocator()->get('KapMessage\Service\Message'));
                    return $ins;
                },
                'messengerDelivery' => function($sm) {
                    $ins = new View\Helper\Delivery($sm->getServiceLocator()->get('KapMessage\Service\Delivery'));
                    return $ins;
                }
            )
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'KapMessage\Entity\Message' => 'KapMessage\Entity\Message',
                'KapMessage\Entity\Delivery' => 'KapMessage\Entity\Delivery',
                'KapMessage\Entity\Part' => 'KapMessage\Entity\Part',
            ),
            'factories' => array(
                'KapMessage\FileManager\MessagePartFileVolume' => function ($sm) {
                    $options = new \KapitchiFileManager\Volume\LocalFilesystemVolumeOptions(array(
                        'system' => true,
                        'label' => 'Message Files',
                        'pathRoot' => './data/message-parts'
                    ));
                    return new \KapitchiFileManager\Volume\LocalFilesystemVolume($options);
                },
                //Message
                'KapMessage\Service\Message' => function ($sm) {
                    $ins = new Service\Message(
                        $sm->get('KapMessage\Mapper\Message'),
                        $sm->get('KapMessage\Entity\Message'),
                        $sm->get('KapMessage\Entity\MessageHydrator')
                    );
                    $ins->setDeliveryService($sm->get('KapMessage\Service\Delivery'));
                    $ins->setPartService($sm->get('KapMessage\Service\Part'));
                    return $ins;
                },
                'KapMessage\Mapper\Message' => function ($sm) {
                    return new EntityDbAdapterMapper(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        $sm->get('KapMessage\Entity\Message'),
                        $sm->get('KapMessage\Entity\MessageHydrator'),
                        'message'
                    );
                },
                'KapMessage\Entity\MessageHydrator' => function ($sm) {
                    return new Entity\MessageHydrator(false);
                },
                'KapMessage\Entity\MessageInputFilter' => function ($sm) {
                    $ins = new Entity\MessageInputFilter();
                    return $ins;
                },
                'KapMessage\Form\MessageInputFilter' => function ($sm) {
                    $ins = new Form\MessageInputFilter();
                    return $ins;
                },
                'KapMessage\Form\Message' => function ($sm) {
                    $ins = new Form\Message();
                    $ins->setInputFilter($sm->get('KapMessage\Form\MessageInputFilter'));
                    return $ins;
                },
                //Delivery
                'KapMessage\Service\Delivery' => function ($sm) {
                    $s = new Service\Delivery(
                        $sm->get('KapMessage\Mapper\Delivery'),
                        $sm->get('KapMessage\Entity\Delivery'),
                        $sm->get('KapMessage\Entity\DeliveryHydrator')
                    );
                    return $s;
                },
                'KapMessage\Mapper\Delivery' => function ($sm) {
                    return new EntityDbAdapterMapper(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        $sm->get('KapMessage\Entity\Delivery'),
                        $sm->get('KapMessage\Entity\DeliveryHydrator'),
                        'message_delivery'
                    );
                },
                'KapMessage\Entity\DeliveryHydrator' => function ($sm) {
                    return new Entity\DeliveryHydrator(false);
                },
                //Part
                'KapMessage\Service\Part' => function ($sm) {
                    $s = new Service\Part(
                        $sm->get('KapMessage\Mapper\Part'),
                        $sm->get('KapMessage\Entity\Part'),
                        $sm->get('KapMessage\Entity\PartHydrator')
                    );
                    return $s;
                },
                'KapMessage\Mapper\Part' => function ($sm) {
                    return new EntityDbAdapterMapper(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        $sm->get('KapMessage\Entity\Part'),
                        $sm->get('KapMessage\Entity\PartHydrator'),
                        'message_part'
                    );
                },
                'KapMessage\Entity\PartHydrator' => function ($sm) {
                    return new \Zend\Stdlib\Hydrator\ClassMethods(false);
                },
            )
        );
    }
    
    public function getDir() {
        return __DIR__;
    }

    public function getNamespace() {
        return __NAMESPACE__;
    }

}