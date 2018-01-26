<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="product")
 */

class Product 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=48)
     */
    private $id;

    /** 
     * @ORM\ManyToMany(targetEntity="Collection", inversedBy="products")
     * @ORM\JoinTable(name="products_collections")
     */
    protected $collections;
    
    /**
     * @ORM\Column
     */    
    protected $title;
    
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
    
    public function getCollections() 
    {
        return $this->collections;
    }

    public function __construct() {
        $this->collections = new ArrayCollection();
    }
}


