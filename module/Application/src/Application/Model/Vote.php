<?php

namespace Application\Model;

class Vote implements VoteInterface
{
    protected $dateTime;
    protected $pageId;
    protected $userId;
    protected $value;
    
    public function getDateTime()
    {    
        return $this->dateTime;
    }

    public function getPageId()
    {    
        return $this->pageId;
    }

    public function getUserId()
    {    
        return $this->userId;
    }

    public function getValue()            
    {    
        return $this->value;
    }

}
