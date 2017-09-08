<?php

namespace LazadaApi\Product;


class Product
{
    /**
     * @var integer
     */
    public $PrimaryCategory;

    /**
     * @var array
     */
    public $Attributes;

    /**
     * @var array
     */
    public $Skus;


    /**
     * @param array $Skus
     * @return $this
     */
    public function setSkus($Skus)
    {
        $this->Skus = $Skus;
        return $this;
    }


    /**
     * @param array $Attributes
     * @return $this
     */
    public function setAttributes($Attributes)
    {
        $this->Attributes = $Attributes;
        return $this;
    }


    /**
     * @param int $PrimaryCategory
     * @return $this
     */
    public function setPrimaryCategory($PrimaryCategory)
    {
        $this->PrimaryCategory = $PrimaryCategory;
        return $this;
    }

}