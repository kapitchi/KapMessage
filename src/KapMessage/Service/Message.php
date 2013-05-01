<?php

/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapMessage\Service;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class Message extends \KapitchiEntity\Service\EntityService
{
    protected $deliveryService;
    protected $partService;
    
    public function send(\KapMessage\Entity\Message $message, $recipientIds = array())
    {
        $message->setSentTime(new \DateTime());
        $this->persist($message, array(
            'recipientIds' => $recipientIds
        ));
        
        $this->triggerEvent('send', array(
            'message' => $message,
            'recipientIds' => $recipientIds
        ));
    }
    
    public function read(\KapMessage\Entity\Message $message, $identityId)
    {
        $deliveryService = $this->getDeliveryService();
        $delivery = $deliveryService->findOneBy(array(
            'messageId' => $message->getId(),
            'ownerId' => $identityId
        ));
        
        //read already?
        if(!$delivery || $delivery->getReadTime()) {
            return $delivery;
        }
        
        $delivery->setReadTime(new \DateTime());
        $deliveryService->persist($delivery);
        
        return $delivery;
    }
    
    public function replyTo($message, $replyMessage, $senderId, $replyToAll = false)
    {
        $replyMessage = $this->createEntityFromArray($replyMessage);
        
        //copy some stuff?
        if(!$replyMessage->getTypeHandle()) {
            $replyMessage->setTypeHandle($message->getTypeHandle());
        }
        
        $replyMessage->setReplyToMessageId($message->getId());
        $replyMessage->setSenderId($senderId);
        
        $recipientIds = array($message->getSenderId());
        if($replyToAll) {
            $recipientIds = $this->getAllRecipientIds($message, $senderId);
        }
        
        $this->send($replyMessage, $recipientIds);
        
        return $replyMessage;
    }
    
    public function getAllRecipientIds($message, $excludeIds = null)
    {
        $excludeIds = (array)$excludeIds;
        
        $x = $this->getDeliveryService()->fetchAll(array(
            'messageId' => $message->getId()
        ));
       
        $ret = array();
        foreach($x as $delivery) {
            $ret[] = $delivery->getOwnerId();
        }
        
        $ret[] = $message->getSenderId();
        
        $ret = array_diff($ret, $excludeIds);
        return $ret;
    }
    
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $instance = $this;
        
        $em = $this->getEventManager();
        $em->attach('persist', function($e) use ($instance) {
            $deliveryService = $instance->getDeliveryService();
            $data = $e->getParam('data');
            if(isset($data['recipientIds'])) {
                $recPersists = array();
                foreach($data['recipientIds'] as $recId) {
                    $delivery = $deliveryService->createEntityFromArray(array(
                        'ownerId' => $recId,
                        'messageId' => $e->getParam('entity')->getId()
                    ));
                    $recPersists[] = $deliveryService->persist($delivery);
                }
                $e->setParam('deliveryPersists', $recPersists);
            }
        });
    }
    
    public function loadModel($messageOrId)
    {
        $model = new \KapMessage\Model\Message();
        $message = $messageOrId;
        if(!$this->isEntityInstance($messageOrId)) {
            $message = $this->get($messageOrId);
        }
        
        $model->setMessage($message);
        $model->setParts($this->getAllParts($message));
        return $model;
    }
    
    public function persistModel(\KapMessage\Model\Message $model)
    {
        $message = $model->getMessage();
        $this->persist($message);
        
        $deliveryService = $this->getDeliveryService();
        foreach($model->getDeliveries() as $delivery) {
            $delivery->setMessageId($message->getId());
            $deliveryService->persist($delivery);
        }
    }
    
    public function getAllParts($message)
    {
        $parts = $this->getPartService()->fetchAll(array(
            'messageId' => $message->getId()
        ));
        
        return $parts;
    }
    
    public function getDeliveryService()
    {
        return $this->deliveryService;
    }

    public function setDeliveryService($deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }
    
    public function getPartService()
    {
        return $this->partService;
    }

    public function setPartService($partService)
    {
        $this->partService = $partService;
    }

}