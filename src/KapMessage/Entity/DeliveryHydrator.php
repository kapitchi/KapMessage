<?php

/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapMessage\Entity;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class DeliveryHydrator extends \Zend\Stdlib\Hydrator\ClassMethods
{
    public function extract($object) {
        $data = parent::extract($object);
        if($data['receivedTime'] instanceof \DateTime) {
            $data['receivedTime'] = $data['receivedTime']->format('Y-m-d\TH:i:sP');//UTC
        }
        if($data['readTime'] instanceof \DateTime) {
            $data['readTime'] = $data['readTime']->format('Y-m-d\TH:i:sP');//UTC
        }
        return $data;
    }

    public function hydrate(array $data, $object) {
        if(!empty($data['receivedTime']) && !$data['receivedTime'] instanceof \DateTime) {
            $data['receivedTime'] = new \DateTime($data['receivedTime']);
        }
        if(!empty($data['readTime']) && !$data['readTime'] instanceof \DateTime) {
            $data['readTime'] = new \DateTime($data['readTime']);
        }
        return parent::hydrate($data, $object);
    }
}