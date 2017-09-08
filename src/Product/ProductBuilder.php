<?php


namespace LazadaApi\Product;


use LazadaApi\Exception;

class ProductBuilder
{

    protected $PrimaryCategory;


    /**
     * @var Attribute[]
     */
    protected $ListAttribute;


    /**
     * @var Attribute[]
     */
    protected $ListSku;


    /**
     * @var array
     */
    protected $Attributes;


    /**
     * @var array
     */
    protected $Skus;

    /**
     *
     * ProductBuilder constructor.
     * @param int $category_id
     * @internal param $input
     */
    public function __construct($category_id = 0)
    {
        if ($category_id) {
            $this->PrimaryCategory = $category_id;
        }
    }


    /**
     * @param int $category_id
     * @return $this
     */
    public function setPrimaryCategory($category_id)
    {
        $this->PrimaryCategory = $category_id;
        return $this;
    }


    /**
     * @param $category_id
     * @param $user
     * @param $api_token
     * @return $this
     * @throws Exception
     */
    public function fetchAttributeByPrimaryCategory($category_id, $user, $api_token)
    {
        if ($category_id == null) {
            throw new Exception('category_id is invalid');
        }
        $this->PrimaryCategory = $category_id;

        $request = new GetCategoryAttributes($user, $api_token);
        $request->PrimaryCategory = $category_id;
        $attributes = $request->execute();

        $this->applyAttributes($attributes);
        return $this;
    }


    /**
     * @param array $attributes
     */
    public function applyAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            $attribute = new Attribute($attribute);

            if ($attribute->attributeType == 'sku') {
                $this->ListSku[$attribute->name] = $attribute;
            } elseif ($attribute->attributeType == 'normal') {
                $this->ListAttribute[$attribute->name] = $attribute;

            }
        }
    }


    /**
     * @param array $attributes_values
     * @return $this
     */
    public function setAttributesValues(array $attributes_values)
    {
        $sku_collection = [];
        $att_collection = [];
        $list_attribute_sku = $this->ListSku + $this->ListAttribute;

        foreach ($attributes_values as $name => $value) {
            if (isset($list_attribute_sku[$name])) {
                $attribute = &$list_attribute_sku[$name];

                if ($attribute->attributeType == 'sku') {
                    $sku_collection[$name] = $value;

                } elseif ($attribute->attributeType == 'normal') {
                    $att_collection[$name] = $value;

                }
            }
        }

        $this->Attributes = $att_collection;
        $this->Skus = [$sku_collection];
        return $this;
    }


    /**
     * @param $attribute_name
     * @param $value
     * @return $this
     */
    public function setAttributeValue($attribute_name, $value)
    {
        if (isset($this->ListAttribute[$attribute_name])) {
            $this->Attributes[$attribute_name] = $value;
        }
        return $this;
    }


    /**
     * @param array $skus_values
     * @return $this
     */
    public function addSkusValues(array $skus_values)
    {
        $sku_collection = [];
        foreach ($skus_values as $name => $value) {
            if (isset($this->ListAttribute[$name])) {

                $attribute = &$this->ListAttribute[$name];

                if ($attribute->attributeType == 'sku') {
                    $sku_collection[$name] = $value;
                }
            }
        }
        $this->Skus[] = $sku_collection;
        return $this;
    }


    /**
     * @return Product
     * @throws Exception
     */
    public function build()
    {
        if ($this->PrimaryCategory == null) {
            throw new Exception('Product does not have PrimaryCategory.');
        }

        $product = new Product();
        $product->Attributes = $this->Attributes;
        $product->Skus = $this->Skus;
        $product->PrimaryCategory = $this->PrimaryCategory;

        return $product;
    }


    public function buildXmlProductRequest()
    {
        $product = $this->build();
        $data = array_from($product);

        return xml_from_array([
            'Product' => $data
        ], 'Request');
    }


    /**
     * @return Attribute[]
     */
    public function getListSku()
    {
        return $this->ListSku;
    }

    /**
     * @return Attribute[]
     */
    public function getListAttribute()
    {
        return $this->ListAttribute;
    }


}