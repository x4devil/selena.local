<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRequestProduct
 */
class UserRequestProduct
{
    /**
     * @var float
     */
    private $price;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\UserRequest
     */
    private $userRequest;

    /**
     * @var \AppBundle\Entity\Product
     */
    private $product;


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
     * Set idUserRequest
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
     * Get idUserRequest
     *
     * @return \AppBundle\Entity\UserRequest 
     */
    public function getUserRequest()
    {
        return $this->userRequest;
    }

    /**
     * Set idProduct
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
     * Get idProduct
     *
     * @return \AppBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
