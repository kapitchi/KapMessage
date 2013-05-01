<?php
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