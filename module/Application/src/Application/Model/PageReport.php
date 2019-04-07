<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use \Application\Mapper\PageMapperInterface;

/**
 * Description of PageReport
 *
 * @author Alexander
 */
class PageReport implements PageReportInterface
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var int
     */
    protected $pageId;
    
    /**
     * @var string
     */
    protected $reporter;
    
    /**
     * 
     * @var \DateTime
     */
    protected $date;
    
    /**
     * @var int
     */
    protected $status;

    /**
     * @var int
     */
    protected $oldStatus;
    
    /**
     * @var int
     */
    protected $originalId;
    
    /**
     * @var int
     */
    protected $kind;

    /**
     * @var int
     */
    protected $oldKind;
    
    /**
     * @var string
     */
    protected $contributors;

    /**
     *
     * @var int
     */
    protected $reportState;
    
    /**
     * @var \Application\Model\PageInterface
     */
    protected $page;
    
    /**
     * @var \Application\Model\PageInterface
     */
    protected $originalPage;

    /**
     *
     * @var \Application\Mapper\PageMapperInterface
     */
    protected $pageMapper;

    /**
     * Constructor
     * @param \Application\Mapper\PageMapperInterface $pageMapper
     */
    public function __construct(PageMapperInterface $pageMapper) 
    { 
        $this->pageMapper = $pageMapper;
        $this->contributors = [];
        $this->reportState = \Application\Utils\ReportState::PENDING;
    }
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function setId($id)
    {
        if (is_null($this->id)) {
            $this->id = $id;
        } else {
            throw new Exception('Attempt to manually change autoincremented id');
        }
        
    }    
    
    /**
     * {@inheritDoc}
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param int $pageId
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }    
    
    /**
     * {@inheritDoc}
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @param string $reporter
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;
    }    

    /**
     * {@inheritDoc}
     */
    public function getDate()
    {
        return $this->date;
    }
    
    public function setDate(\DateTime $value = null)
    {
        $this->date = $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {        
        $this->status = $status;
    }    
    
    /**
     * {@inheritDoc}
     */
    public function getOldStatus()
    {
        return $this->oldStatus;
    }

    /**
     * @param int $status
     */
    public function setOldStatus($status)
    {        
        $this->oldStatus = $status;
    }       
    
    /**
     * {@inheritDoc}
     */
    public function getOriginalId()
    {
        return $this->originalId;
    }

    /**
     * @param int $originalId
     */
    public function setOriginalId($originalId)
    {
        $this->originalId = $originalId;
    }    
    
    /**
     * {@inheritDoc}
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param int $kind
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }

    /**
     * {@inheritDoc}
     */
    public function getOldKind()
    {
        return $this->oldKind;
    }

    /**
     * @param int $kind
     */
    public function setOldKind($kind)
    {
        $this->oldKind = $kind;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getContributors()
    {
        return $this->contributors;
    }        

    /**
     * @param Application\Model\PageReportContributor[] $contributors
     */
    public function setContributors($contributors)
    {
        $this->contributors = $contributors;
    }

    /**
     * {@inheritDoc}
     */
    public function getReportState()
    {
        return $this->reportState;
    }

    /**
     * @param int $state
     */
    public function setReportState($state)
    {
        $this->reportState = $state;
    }
        
    /**
     * @return \Application\Model\PageInterface
     * @throws Exception
     */
    public function getPage()
    {
        if (is_null($this->page)) {
            if (!is_null($this->pageId)) {
                $this->page = $this->pageMapper->find($this->pageId);
//            } else {
//                throw new \Exception('Object PageReport was not initialized');
            }            
        }
        return $this->page;
    }    
    
    
    /**
     * @return \Application\Model\PageInterface
     * @throws Exception
     */
    public function getOriginalPage()
    {
        if (is_null($this->originalPage)) {
            if ($this->originalId) {
                $this->originalPage = $this->pageMapper->find($this->originalId);
//            } else {
//                throw new \Exception('Object PageReport was not initialized');
            }            
        }
        return $this->originalPage;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getContributorsJson()
    {
        $data = [];
        $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
        foreach ($this->getContributors() as $contrib) {
            $data[] = $hydrator->extract($contrib);
        }
        return json_encode($data);
    }
    
    /**
     * @var string $json Json string with array of contributors
     * @throws \InvalidArgumentException
     */
    public function setContributorsJson($json)
    {
        $data = json_decode($json, true);        
        if (is_array($data)) {
            $contributors = [];
            $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
            foreach ($data as $record) {
                $contrib = new PageReportContributor();
                $hydrator->hydrate($record, $contrib);
                if ($contrib->isValid()) {
                    $contributors[] = $contrib;
                } else {
                    throw new \InvalidArgumentException('Value must be a correct json string containing an array of contibutors');
                }
            }            
            $this->setContributors($contributors);
        } else {
            throw new \InvalidArgumentException('Value must be a correct json string containing an array of contibutors');
        }        
    }    
    
    // Methods for hydrator
    
    /**
     * @return string|null
     */
    public function getPageName()
    {
        if ($this->getPage()) {
            return $this->getPage()->getTitle();
        } else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getSiteName()
    {
        if ($this->getPage()) {
            return $this->getPage()->getSite()->getEnglishName();
        } else {
            return null;
        }
    }    

    /**
     * @return string|null
     */
    public function getOriginalPageName()
    {
        if ($this->getOriginalPage()) {
            return $this->getOriginalPage()->getTitle();
        } else {
            return null;
        }
    }
    
    /**
     * @return string|null
     */
    public function getOriginalSiteName()
    {
        if ($this->getOriginalPage()) {
            return $this->getOriginalPage()->getSite()->getEnglishName();
        } else {
            return null;
        }        
    }    

    /**
     * @return int|null
     */
    public function getOriginalSiteId()
    {
        if ($this->getOriginalPage()) {
            return $this->getOriginalPage()->getSite()->getId();
        } else {
            return null;
        }        
    }        
    
    /**
     * @return boolean
     */
    public function hasOriginal()
    {
        return !is_null($this->getOriginalPage());
    }
}
