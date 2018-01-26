<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{
   
    private $productManager;
    
    public function __construct($productManager) 
    {
        $this->productManager = $productManager;
    }
    
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function importAction()
    {
        if($this->getRequest()->isPost()) {
           if ($this->params()->fromPost('clearDB')) {
               $this->productManager->clearAll(); 
           } 
           $this->productManager->importAllFromShopify(); 
           $json = new JsonModel();
           return $json;
        }
        else
           return new ViewModel();
    }  
    
    public function collectionsAction()
    {
        $view = new ViewModel;
        $view->setVariable('collections', $this->productManager->listCollections());
        return $view;
    }

    public function collectionAction()
    {
        $id = $this->params()->fromRoute('id', -1);
        $collection = $this->productManager->getCollection($id);
        $view = new ViewModel;
        $view->setVariable('products', $collection->getProducts()->toArray());
        $view->setVariable('title', $collection->getTitle());
        return $view;
    }

}
