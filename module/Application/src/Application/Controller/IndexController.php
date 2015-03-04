<?php


namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        //$this->flashMessenger()->addSuccessMessage("Mensagem de teste");
        return new ViewModel();
    }
}
