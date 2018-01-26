<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\ProductManager;
use Application\Service\ShopifyAdapter;

/**
 * This is the factory class for UserManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ProductManagerFactory
{
    /**
     * This method creates the UserManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $shopifyAdapter = $container->get(ShopifyAdapter::class);
                        
        return new ProductManager($entityManager, $shopifyAdapter);
    }
}