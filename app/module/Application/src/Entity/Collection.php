<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="collection")
 */

class Collection 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=48)
     */
    private $id;

    /** 
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="collections")
     */
    protected $products;
    
    /**
     * @ORM\Column
     */
    private $title;
    
    public function getId() 
    {
        return $this->id;
    }

    public function setId($id) 
    {
        $this->id = $id;
    }
    
    public function getTitle() 
    {
        return $this->title;
    }

    public function setTitle($title) 
    {
        $this->title = $title;
    }    
    
    public function getProducts() 
    {
        return $this->products;
    }    
    
    public function __construct() {
        $this->products = new ArrayCollection();
    }
}


