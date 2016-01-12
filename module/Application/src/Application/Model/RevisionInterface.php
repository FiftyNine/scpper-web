<?php

namespace Application\Model;

interface RevisionInterface
{
    public function getRevisionId();
    
    public function getPageId();
    
    public function getRevisionIndex();
    
    public function getUserId();
    
    public function getDateTime();
    
    public function getComments();
}
