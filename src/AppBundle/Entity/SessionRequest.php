<?php

namespace AppBundle\Entity;

class SessionRequest {
    protected $product;
    protected $amount;
    
    public function __construct() {
        $this->product = null;
        $this->amount = 1;
    }
    
    public function getProduct() {
        return $this->product;
    }
    
    public function setProduct(Product $product) {
        $this->product = $product;
        return $this;
    }
    
    public function getAmount() {
        return $this->amount;
    }
    
    public function setAmount($amount) {
        $this->amount = $amount;
        return $this;
    }
}
