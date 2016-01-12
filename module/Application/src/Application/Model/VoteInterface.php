<?php

namespace Application\Model;

interface VoteInterface
{
    public function getPageId();
    
    public function getUserId();
    
    public function getValue();
    
    public function getDateTime();
}