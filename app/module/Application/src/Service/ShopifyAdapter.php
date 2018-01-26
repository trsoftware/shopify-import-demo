<?php
namespace Application\Service;

use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Http\Client\Adapter\Curl;

/**
 * Description of ShopifyAdapter
 *
 * @author ost
 */

class ShopifyAdapter {
   
    private $http;
    
    private $config = array(
        'storefront_access_token' => '4cc89fac732e6ab0c1c11a5ffee47197',
        'shop'    => 'api2cart-contest.myshopify.com'
    );  
    
    const QUERY_COLLECTIONS = 
            '{
                shop {
                  collections(first: 250) {
                    edges {
                      node {
                        id
                        title
                      }
                      cursor
                    }
                  }
                }
            }';
    
    const QUERY_PRODUCTS = 
            '{
                shop {
                  products(first: 250) {
                    edges {
                      node {
                        id
                        title
                        collections(first: 250) {
                          edges {
                            node {
                              id
                            }
                          }
                        }
                      }
                      cursor
                    }
                  }
                }
            }';    
    
    public function __construct()
    {
        $adapter = new Curl();

        $this->http = new Client();
        $this->http->setAdapter($adapter);
    }
    
    private function request($query = null)
    {
        $req = new Request();
        $req->setUri('https://' . $this->config['shop'] . '/api/graphql');
        $req->getHeaders()->addHeaders(array(
            'X-Shopify-Storefront-Access-Token' => $this->config['storefront_access_token'],
            'Content-Type' => 'application/graphql'
        ));                    
        if (!is_null($query))
        {
                $req->setContent($query);
        };        
        
      	$req->setMethod(Request::METHOD_POST);
        
        $response = $this->http->dispatch($req);

        if ($response->isSuccess()) {
            return json_decode($response->getBody());
        }
        else 
        {
        	throw new \Exception('Response returned status: (' . $response->getStatusCode() . ') ' . $response->getReasonPhrase() .' BODY: '. $response->getBody());
        }
        
    }        
    
    public function listCollections()
    {
        $resp = $this->request(ShopifyAdapter::QUERY_COLLECTIONS);
        $collections = array();
        foreach($resp->data->shop->collections->edges as $edge) {
            $collections[] = [
                                'id' => $edge->node->id,
                                'title' => $edge->node->title   
                            ];
        }
        return $collections;
    }        

    public function listAllProducts()
    {
        $resp = $this->request(ShopifyAdapter::QUERY_PRODUCTS);
        $products = array();
        foreach($resp->data->shop->products->edges as $prodedge) {
            $collections = array();
            foreach ($prodedge->node->collections->edges as $coledge) {
                $collections[] = $coledge->node->id;
            }
            $products[] = [
                                'id' => $prodedge->node->id,
                                'title' => $prodedge->node->title,   
                                'collections' => $collections
                            ];
        }
        return $products;    
        
    }        

    
}
