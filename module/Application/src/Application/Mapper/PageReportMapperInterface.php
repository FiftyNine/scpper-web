<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Application\Model\PageReportInterface;

/**
 *
 * @author Alexander
 */
interface PageReportMapperInterface extends SimpleMapperInterface
{
   /**
    * @param \Application\Model\PageReportInterface $report
    *
    * @return \Application\Model\PageReportInterface
    * @throws \Exception
    */
   public function save(PageReportInterface $report);
   
   /**
    * @param \Application\Model\PageReportInterface $report
    * @return \Application\Model\PageReportInterface
    * @throws \Exception
    */
   public function apply(PageReportInterface $report);
}
