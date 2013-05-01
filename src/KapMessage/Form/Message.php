<?php

/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapMessage\Form;

use KapitchiBase\Form\EventManagerAwareForm;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class Message extends EventManagerAwareForm
{
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'options' => array(
                'label' => $this->translate('ID'),
            ),
        ));
        
        $this->add(array(
            'name' => 'senderId',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $this->translate('Sender'),
            ),
            'attributes' => array(
                'data-kap-ui' => 'identity-lookup-input',
            )
        ));
        
        $this->add(array(
            'name' => 'typeHandle',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => $this->translate('Type'),
                'value_options' => array(
                    'default' => 'Default'
                )
            ),
        ));
        
        $this->add(array(
            'name' => 'sentTime',
            'type' => 'Zend\Form\Element\DateTime',
            'options' => array(
                'label' => $this->translate('Sent'),
            ),
        ));
        
        $this->add(array(
            'name' => 'subject',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $this->translate('Subject'),
            ),
        ));
        
        $this->add(array(
            'name' => 'body',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => $this->translate('Message'),
            ),
        ));
        
    }
    
}