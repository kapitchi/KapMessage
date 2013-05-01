<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapMessage\Model;

use KapMessage\Entity\Part;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class Message
{
    protected $message;
    protected $parts;
    protected $deliveries;
    
    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getParts()
    {
        return $this->parts;
    }

    public function setParts(array $parts)
    {
        $this->parts = array();
        foreach ($parts as $part) {
            $this->addPart($part);
        }
    }

    public function addPart(Part $part)
    {
        $this->parts[] = $part;
    }
    
    public function getDeliveries()
    {
        return $this->deliveries;
    }

    public function setDeliveries(array $deliveries)
    {
        $this->deliveries = $deliveries;
    }
    
}