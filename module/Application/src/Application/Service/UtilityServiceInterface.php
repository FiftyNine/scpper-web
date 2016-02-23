<?php

namespace Application\Service;

use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

interface UtilityServiceInterface
{
    /**
     * Takes data from post request and sets chosen site as default via cookies
     * @param Request $request Incoming HTTP request with data from site selector form
     * @param Response $response Outgoing HTTP response where cookie should be set
     */
    public function selectSite(Request $request, Response $response);
    
    /**
     * @return int Id of a current site for which we're building a page
     */
    public function getSiteId();

    /**
     * Generates PHP files with field names as constants
     */
    public function generateDbConstants();
    
    /**
     * Attach a search form to site's layout viewmodel
     * @param ViewModel $viewModel
     */
    public function attachSearchForm(ViewModel $viewModel);
    
    /**
     * Get a search form instance
     * @return \Application\Form\SearchForm
     */
    public function getSearchForm();
}