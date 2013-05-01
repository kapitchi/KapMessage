<?php
namespace KapMessage\Entity;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class Delivery
{
    protected $id;
    protected $messageId;
    protected $ownerId;
    protected $receivedTime;
    protected $readTime;
 
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }
    
    public function getReceivedTime()
    {
        return $this->receivedTime;
    }

    public function setReceivedTime($receivedTime)
    {
        $this->receivedTime = $receivedTime;
    }

    public function getReadTime()
    {
        return $this->readTime;
    }

    public function setReadTime($readTime)
    {
        $this->readTime = $readTime;
    }
    
}