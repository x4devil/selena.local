<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 */
class Images
{
    /**
     * @var string
     */
    private $extend;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Product
     */
    private $product;


    /**
     * Set extend
     *
     * @param string $extend
     * @return Images
     */
    public function setExtend($extend)
    {
        $this->extend = $extend;

        return $this;
    }

    /**
     * Get extend
     *
     * @return string 
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     * @return Images
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
