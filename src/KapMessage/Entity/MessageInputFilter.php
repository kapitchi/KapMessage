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
class MessageInputFilter extends \KapitchiBase\InputFilter\EventManagerAwareInputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'id',
            'required'   => false,
        ));
        $this->add(array(
            'name'       => 'senderId',
            'required'   => true,
        ));
        $this->add(array(
            'name'       => 'typeHandle',
            'required'   => true,
        ));
        $this->add(array(
            'name'       => 'sentTime',
            'required'   => true,
        ));
        $this->add(array(
            'name'       => 'subject',
            'required'   => true,
        ));
        $this->add(array(
            'name'       => 'body',
            'required'   => false,
        ));
        $this->add(array(
            'name'       => 'replyToMessageId',
            'required'   => false,
        ));
    }
}