<?php

namespace LazadaApi\Product;

class Attribute
{
    public $attributeType;
    public $inputType;
    public $isMandatory;
    public $label;
    public $name;
    public $options;


    public function __construct($data)
    {
        is_object($data) && $data = array($data);

        foreach ($data as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }


    static function allInputTypes()
    {
        return [
            "text",
            "richText",
            "singleSelect",
            "multiSelect",
            "numeric",
            "date",
            "img"
        ];
    }


    static function allAttributeTypes()
    {
        return [
            "normal",
            "sku"
        ];
    }

}