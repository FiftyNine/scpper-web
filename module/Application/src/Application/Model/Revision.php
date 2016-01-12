<?php

namespace Application\Model;

class Revision implements RevisionInterface
{
    protected $comments;
    protected $dateTime;
    protected $pageId;
    protected $revisionId;
    protected $revisionIndex;
    protected $userId;
    
    public function getComments()
    {
        return $this->comments;
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }

    public function getPageId()
    {
        return $this->pageId;
    }

    public function getRevisionId()
    {
        return $this->revisionId;
    }

    public function getRevisionIndex()
    {
        return $this->revisionIndex;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
