<?php

/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapMessage\Controller;

use KapitchiEntity\Controller\AbstractEntityController;

class MessageController extends AbstractEntityController
{
    public function getIndexUrl()
    {
        return $this->url()->fromRoute('messenger/message', array(
            'action' => 'index'
        ));
    }

    public function getUpdateUrl($entity)
    {
        return $this->url()->fromRoute('messenger/message', array(
            'action' => 'update', 'id' => $entity->getId()
        ));
    }
    
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $this->getEventManager()->attach('create.post', function($e) {
            //$entity = $e->getParam('entity');
            $viewModel = $e->getParam('viewModel');
            $form = $e->getParam('form');
            $sent = $form->get('sentTime');
            $sent->setValue(date('Y-m-d\TH:i:sP'));
        });
        
        $this->getEventManager()->attach('update.post', function($e) {
            $entity = $e->getParam('entity');
            $viewModel = $e->getParam('viewModel');
            $service = $e->getTarget()->getServiceLocator()->get('KapMessage\Service\Delivery');
            $viewModel->deliveryPaginator = $service->getPaginator(array(
                'messageId' => $entity->getId()
            ));
        });
    }
    
}
