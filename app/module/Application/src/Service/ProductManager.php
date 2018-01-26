<?php

namespace Application\Service;

use Application\Entity\Product;
use Application\Entity\Collection;
use Application\Service\ShopifyAdapter;

/**
 * Description of ProductManager
 *
 * @author ost
 */
class ProductManager {
    
    /**
    * Doctrine entity manager.
    * @var Doctrine\ORM\EntityManager
    */
    private $entityManager; 

    /**
    * @var Application\Service\ShopifyAdapter
    */
    private $shopifyAdapter;
    
    /**
    * Constructs the service.
    */
    public function __construct($entityManager, $shopifyAdapter) 
    {
        $this->entityManager = $entityManager;
        $this->shopifyAdapter = $shopifyAdapter;
    }
    
    public function addProduct($data) {
        if ($this->entityManager->getRepository(Product::class)->find($data['id']) == null) {
            
            $product = new Product();
            $product->setId($data['id']);
            $product->setTitle($data['title']);
            foreach ($data['collections'] as $collid) {
                $collection = $this->entityManager->getRepository(Collection::class)->find($collid);
                if ($collection == null) {
                    throw new \Exception('Invalid collection ID');
                };
                $product->getCollections()->add($collection);
            }
            
            $this->entityManager->persist($product);
            $this->entityManager->flush();            
        }       
        
    }
    
    public function addCollection($data) {
        if ($this->entityManager->getRepository(Collection::class)->find($data['id']) == null) {

            $collection = new Collection();
            $collection->setId($data['id']);
            $collection->setTitle($data['title']);
            
            $this->entityManager->persist($collection);
        
            $this->entityManager->flush();
        }       
        
    }    
    
    public function importAllFromShopify() {
        $collections = $this->shopifyAdapter->listCollections();
        foreach ($collections as $collection) {
            $this->addCollection($collection);
        }
        $products = $this->shopifyAdapter->listAllProducts();
        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }
    
    public function listCollections() {
       return $this->entityManager->getRepository(Collection::class)->findAll(); 
    }
    
    public function getCollection($collid) {
        $collection = $this->entityManager->getRepository(Collection::class)->find($collid); 
        if ($collection == null) {
            throw new \Exception('Invalid collection ID');
        };
        return $collection;
    }
    
    public function clearAll() {
        $connection = $this->entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $connection->executeUpdate($platform->getTruncateTableSQL('product', false));        
        $connection->executeUpdate($platform->getTruncateTableSQL('collection', false));        
        $connection->executeUpdate($platform->getTruncateTableSQL('products_collections', false));        
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }
            
        
}
