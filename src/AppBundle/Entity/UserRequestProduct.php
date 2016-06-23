<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRequestProduct
 *
 * @ORM\Table(name="user_request_product", indexes={@ORM\Index(name="id_product", columns={"id_product"}), @ORM\Index(name="id_user_request", columns={"id_user_request"})})
 * @ORM\Entity
 */
class UserRequestProduct
{
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="smallint", nullable=false)
     */
    private $amount;

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
     * @var \AppBundle\Entity\UserRequest
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserRequest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user_request", referencedColumnName="id")
     * })
     */
    private $userRequest;



    /**
     * Set price
     *
     * @param float $price
     * @return UserRequestProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return UserRequestProduct
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
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
     * @return UserRequestProduct
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

    /**
     * Set userRequest
     *
     * @param \AppBundle\Entity\UserRequest $userRequest
     * @return UserRequestProduct
     */
    public function setUserRequest(\AppBundle\Entity\UserRequest $userRequest = null)
    {
        $this->userRequest = $userRequest;

        return $this;
    }

    /**
     * Get userRequest
     *
     * @return \AppBundle\Entity\UserRequest 
     */
    public function getUserRequest()
    {
        return $this->userRequest;
    }
}
