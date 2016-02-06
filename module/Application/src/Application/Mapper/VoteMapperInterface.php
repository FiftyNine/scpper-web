<?php

namespace Application\Mapper;

use Application\Utils\VoteType;

interface VoteMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns number of site votes
     * @param int $siteId
     * @param int $type
     * @param \DateTime $castAfter Count only votes cast after date
     * @return int
     */
    public function countSiteVotes($siteId, $type = VoteType::ANY, \DateTime $castAfter = null, \DateTime $castBefore = null);
    
    /**
     * Return votes for a certain page
     * @param int $pageId
     * @return VoteInterface[]
     */
    public function findVotesOnPage($pageId);    
    
    /**
     * Get an aggregated results from votes
     * @param array[string]string
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param \DateTime $castAfter
     * @param \DateTime $castBefore
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($conditions, $aggregates, \DateTime $castAfter = null, \DateTime $castBefore = null, $order = null, $paginated = false);
}