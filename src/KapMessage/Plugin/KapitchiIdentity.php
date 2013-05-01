<?php

/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapMessage\Plugin;

use Zend\EventManager\EventInterface,
    KapitchiApp\PluginManager\PluginInterface;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class KapitchiIdentity implements PluginInterface
{
    
    public function getAuthor()
    {
        return 'Matus Zeman';
    }

    public function getDescription()
    {
        return 'TODO';
    }

    public function getName()
    {
        return '[KapMessage] KapitchiIdentity related stuff';
    }

    public function getVersion()
    {
        return '0.1';
    }
    
    public function onBootstrap(EventInterface $e)
    {
        $em = $e->getApplication()->getEventManager();
        $sm = $e->getApplication()->getServiceManager();
        $sharedEm = $em->getSharedManager();
        
        //prefill message sender id in the form
        $sharedEm->attach('KapMessage\Controller\MessageController', 'create.post', function($e) use ($sm) {
            $form = $e->getParam('form');
            
            $authService = $sm->get('KapitchiIdentity\Service\Auth');
            if($authService->hasIdentity()) {
                $senderId = $authService->getLocalIdentityId();
                $el = $form->get('senderId');
                $el->setValue($senderId);
            }
        });
        
        //if senderId is not set anyway - set it to currently logged in identity
        $sharedEm->attach('KapMessage\Service\Message', 'persist', function($e) use ($sm) {
            $authService = $sm->get('KapitchiIdentity\Service\Auth');
            if($authService->hasIdentity()) {
                $entity = $e->getParam('entity');
                $senderId = $authService->getLocalIdentityId();
                $entity->setSenderId($senderId);
            }
        }, 10);
        
    }
    
}