<?php
namespace KapMessage\Controller;

class InboxController extends \Zend\Mvc\Controller\AbstractActionController
{
    protected $messageService;
    protected $deliveryService;
    protected $authService;
    protected $replyForm;
    
    public function indexAction()
    {
        $deliveryPaginator = $this->getDeliveryService()->getPaginator(array(
            'ownerId' => $this->getLocalIdentityId()
        ), array(
            'id DESC'
        ));
        
        return array(
            'deliveryPaginator' => $deliveryPaginator,
        );
    }
    
    public function sentAction()
    {
        $messagePaginator = $this->getMessageService()->getPaginator(array(
            'senderId' => $this->getLocalIdentityId()
        ), array(
            'sentTime DESC'
        ));
        
        return array(
            'messagePaginator' => $messagePaginator,
        );
    }
    
    public function createAction()
    {
        $form = $this->getServiceLocator()->get('KapMessage\Form\Message');
        $form->remove('id');
        $form->remove('senderId');
        $form->remove('sentTime');
        $form->remove('typeHandle');
        
        $form->setValidationGroup(array('subject', 'body'));
        
        $this->getEventManager()->trigger('send.pre', $this, array(
            'form' => $form
        ));
        
        if($this->getRequest()->isPost()) {
            $messageData = $this->getRequest()->getPost()->toArray();
            $form->setData($messageData);
            if($form->isValid()) {
                $data = $form->getData();
                $message = $this->getMessageService()->createEntityFromArray($data);
                $message->setTypeHandle('default');
                $message->setSentTime(new \DateTime());
                $message->setSenderId($this->getLocalIdentityId());
                //$this->getMessageService()->send($message, array(1));
                $this->getMessageService()->persist($message, $data);
                
                $this->flashMessenger()->addMessage('Message has been sent');
        
                $this->redirect()->toRoute('messenger/inbox', array('action' => 'index'));
            }
        }
        
        return array(
            'form' => $form,
        );
    }
    
    public function readAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $entity = $this->getMessageService()->get($id);
        
        $replyForm = $this->getServiceLocator()->get('KapMessage\Form\Message');
        $replyForm->remove('id');
        $replyForm->remove('senderId');
        $replyForm->remove('sentTime');
        $replyForm->remove('typeHandle');
        $replyForm->setValidationGroup(array('subject', 'body'));
        
        if($this->getRequest()->isPost()) {
            $replyMessageData = $this->getRequest()->getPost()->toArray();
            $replyForm->setData($replyMessageData);
            if($replyForm->isValid()) {
                $data = $replyForm->getData();
                if(!empty($replyMessageData['reply-all-submit-btn'])) {
                    $replyMessage = $this->getMessageService()->replyTo($entity, $data, $this->getLocalIdentityId(), true);
                }
                else {
                    $replyMessage = $this->getMessageService()->replyTo($entity, $data, $this->getLocalIdentityId());
                }
                $this->redirect()->toRoute('messenger/inbox', array('action' => 'read', 'id' => $replyMessage->getId()));
            }
        }
        
        $subjectEl = $replyForm->get('subject');
        if(!$subjectEl->getValue()) {
            $subject = $entity->getSubject();
            if(strpos($subject, 'Re: ') !== 0) {
                $subject = "Re: " . $subject;
            }
            $subjectEl->setValue($subject);
        }
        
        $delivery = $this->getMessageService()->read($entity, $this->getLocalIdentityId());
        
        return array(
            'replyForm' => $replyForm,
            'delivery' => $delivery,
            'message' => $entity
        );
    }
    
    public function replyAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $entity = $this->getMessageService()->get($id);
        $replyForm = $this->getServiceLocator()->get('KapMessage\Form\Message');
        
        return array(
            'replyForm' => $replyForm,
            'delivery' => $delivery,
            'message' => $entity
        );
    }
    
    protected function getLocalIdentityId()
    {
        return $this->getAuthService()->getLocalIdentityId();
    }


    public function getMessageService()
    {
        return $this->messageService;
    }

    public function setMessageService($messageService)
    {
        $this->messageService = $messageService;
    }
    
    public function getDeliveryService()
    {
        return $this->deliveryService;
    }

    public function setDeliveryService($deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }
        
    public function getAuthService()
    {
        return $this->authService;
    }

    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }

}
