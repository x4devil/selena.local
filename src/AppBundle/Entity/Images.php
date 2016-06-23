<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 *
 * @ORM\Table(name="images", indexes={@ORM\Index(name="id_product", columns={"id_product"})})
 * @ORM\Entity
 */
class Images
{
    /**
     * @var string
     *
     * @ORM\Column(name="extend", type="string", length=5, nullable=false)
     */
    private $extend;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     * })
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
