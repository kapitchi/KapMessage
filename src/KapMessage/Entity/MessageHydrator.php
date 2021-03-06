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
class MessageHydrator extends \Zend\Stdlib\Hydrator\ClassMethods
{
    public function extract($object) {
        $data = parent::extract($object);
        if($data['sentTime'] instanceof \DateTime) {
            $data['sentTime'] = $data['sentTime']->format('Y-m-d\TH:i:sP');//UTC
        }
        return $data;
    }

    public function hydrate(array $data, $object) {
        if(!empty($data['sentTime']) && !$data['sentTime'] instanceof \DateTime) {
            $data['sentTime'] = new \DateTime($data['sentTime']);
        }
        return parent::hydrate($data, $object);
    }
}