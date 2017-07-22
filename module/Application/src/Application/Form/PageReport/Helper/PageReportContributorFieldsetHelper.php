<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PageReportContributorFieldsetHelper
 *
 * @author Alexander
 */

namespace Application\Form\PageReport\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\ElementInterface;
use Zend\Form\Fieldset;
use Zend\View\Renderer\RendererInterface;
use Application\Form\PageReport\PageReportContributorFieldset as Contributor;

class PageReportContributorFieldsetHelper extends AbstractHelper
{
    public function __construct(RendererInterface $view)
    {
        $this->view = $view;
    }
    
    public function __invoke(ElementInterface $fieldset = null)
    {
        if (!$fieldset) {
            return $this;
        } else if ($fieldset instanceOf Fieldset) {            
            return vsprintf('
                <fieldset class="page-report-contributor">
                    <div class="form-group">
                        %s
                        <div class="col-sm-2 text-right">
                            %s
                        </div>
                        <div class="col-sm-4">
                            %s
                        </div>
                        <div class="col-sm-1 text-right">
                            %s
                        </div>
                        <div class="col-sm-5 contributor-role-button">
                            %s
                            %s
                            %s
                            %s
                        </div>
                    </div>
                </fieldset>',
                [
                    $this->view->formElement($fieldset->get(Contributor::USER_ID)),
                    $this->view->formLabel($fieldset->get(Contributor::USER_NAME)),
                    $this->view->formElement($fieldset->get(Contributor::USER_NAME)),
                    $this->view->formLabel($fieldset->get(Contributor::ROLE)),
                    $this->view->formElement($fieldset->get(Contributor::ROLE)),
                    $this->view->formButton()->openTag($fieldset->get(Contributor::USER_DELETE)),
                    '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',
                    $this->view->formButton()->closeTag()                    
                ]);
        } else { 
            throw new Exception('Not a fieldset');
        }
    }
}
