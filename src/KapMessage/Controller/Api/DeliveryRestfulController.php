<?php
namespace KapMessage\Controller\Api;

use Zend\View\Model\JsonModel,
    KapitchiEntity\Controller\EntityRestfulController;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class DeliveryRestfulController extends EntityRestfulController
{
//    public function readswitchAction()
//    {
//        $id = $this->getEntityId();
//        if(!$this->getRequest()->isPut()) {//update
//            throw new \Exception("Put method only");
//        }
//        $service = $this->getEntityService();
//        $msg = $service->find($id);
//        if(!$msg) {
//            throw new \Exception("No entity found");
//        }
//        
//        $state = $this->getPut()->get('state');
//        $service->setMessageRead($msg, (bool)$state);
//        
//        $jsonModel = new JsonModel(array(
//            'id' => $id,
//            'entity' => $service->createArrayFromEntity($msg)
//        ));
//        
//        return $jsonModel;
//    }
}