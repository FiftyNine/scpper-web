<?php

namespace Application\Service;

use Application\Mapper\PageMapperInterface;
use Application\Mapper\UserMapperInterface;
use Application\Mapper\AuthorshipMapperInterface;
use Application\Utils\PageStatus;
use Application\Utils\DbConsts\DbViewAuthors;
use Application\Utils\DbConsts\DbViewUsers;

class PageService implements PageServiceInterface 
{
    /**
     *
     * @var PageMapperInterface
     */
    protected $mapper;

    /**
     *
     * @var UserMapperInterface
     */
    protected $userMapper;    
    
    /**
     *
     * @var AuthorshipMapperInterface
     */
    protected $authorshipMapper;    

    /**
     * Fill Authorship objects with respective User objects
     * @param AuthorshipInterface[] $authors
     */
    private function fillAuthorshipUsers($authors)
    {
        $userIds = [];
        foreach ($authors as $author) {
            $userIds[] = $author->getUserId();
        }
        if (count($userIds) > 0) {
            $users = $this->userMapper->findAll([
                sprintf('%s IN (%s)', DbViewUsers::USERID, implode(',', $userIds))
            ]);
            $usersByIds = [];
            foreach ($users as $user) {
                $usersByIds[$user->getId()] = $user;
            }
            foreach ($authors as $author) {
                if (array_key_exists($author->getUserId(), $usersByIds)) {
                    $author->setUser($usersByIds[$author->getUserId()]);
                }
            }
        }        
    }
    
    /**
     * Fill Page objects with respective Authorship objects
     * @param PageInterface[] $pages
     */
    private function fillPagesAuthorships($pages)
    {
        $pageIds = [];
        foreach ($pages as $page) {
            $pageIds[] = $page->getId();
        }
        if (count($pageIds) > 0) {
            $authors = iterator_to_array($this->authorshipMapper->findAll([
                sprintf('%s IN (%s)', DbViewAuthors::PAGEID, implode(',', $pageIds))
            ]));
            $this->fillAuthorshipUsers($authors);
            $authorsByPageIds = [];
            foreach ($authors as $author) {
                $pageId = $author->getPageId();
                if (!array_key_exists($pageId, $authorsByPageIds)) {
                    $authorsByPageIds[$pageId] = [];
                }
                $authorsByPageIds[$pageId][] = $author;
            }
            foreach ($pages as $page) {
                if (array_key_exists($page->getId(), $authorsByPageIds)) {
                    $page->setAuthors($authorsByPageIds[$page->getId()]);
                }
            }
        }
    }
    
    private function preparePages($pages, $paginated, $page, $perPage)
    {
        if ($paginated && $pages && $page > 0 && $perPage > 0) {            
            $pages->setCurrentPageNumber($page);
            $pages->setItemCountPerPage($perPage);
        } else {
            $pages = iterator_to_array($pages);
        }
        if ($pages) {
            $this->fillPagesAuthorships($pages);
        }
        return $pages;
    }
    
    public function __construct(PageMapperInterface $mapper, UserMapperInterface $userMapper, AuthorshipMapperInterface $authorshipMapper)
    {
        $this->mapper = $mapper;
        $this->userMapper = $userMapper;
        $this->authorshipMapper = $authorshipMapper;        
    }
    
    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        return $this->mapper->find($id);
    }
    
    /**
     * {@inheritDoc}
     */    
    public function findAll()
    {
        $pages = $this->mapper->findAll();
        $this->fillPagesAuthorships($pages);
        return $pages;
    }

    /**
     * {@inheritDoc}
     */
    public function findByName($mask, $sites, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10)
    {
        $pages = $this->mapper->findPagesByName($sites, $mask, $deleted, $order, $paginated);
        $pages = $this->preparePages($pages, $paginated, $page, $perPage);
        return $pages;        
    }
    
    /**
     * {@inheritDoc}
     */
    public function countSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $deleted = false)
    {
        return $this->mapper->countSitePages($siteId, $type, $createdAfter, $createdBefore, $deleted);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10)
    {
        $pages = $this->mapper->findSitePages($siteId, $type, $createdAfter, $createdBefore, $deleted, $order, $paginated);
        $pages = $this->preparePages($pages, $paginated, $page, $perPage);
        return $pages;        
    }
        
    /**
     * {@inheritDoc}
     */
    public function findPagesByUser($userId, $siteId, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10)
    {
        $pages = $this->mapper->findPagesByUser($userId, $siteId, $deleted, $order, $paginated);
        $pages = $this->preparePages($pages, $paginated, $page, $perPage);
        return $pages;
    }

    /**
     * {@inheritDoc}
     */
    public function findTranslationsOfUser($userId, $siteId, $order = null, $paginated = false, $page = 1, $perPage = 10)
    {
        $pages = $this->mapper->findTranslationsOfUser($userId, $siteId, $order, $paginated);
        $pages = $this->preparePages($pages, $paginated, $page, $perPage);
        return $pages;        
    }
    
    /**
     * {@inheritDoc}
     */
    public function findPagesByTags($siteId, $includeTags, $excludeTags = [], $all = true, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10)
    {
        $pages = $this->mapper->findPagesByTags($siteId, $includeTags, $excludeTags, $all, $deleted, $order, $paginated);
        $pages = $this->preparePages($pages, $paginated, $page, $perPage);
        return $pages;        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore, $deleted = false)
    {
        return $this->mapper->getAggregatedValues($siteId, $aggregates, $createdAfter, $createdBefore, $deleted);
    }            
}