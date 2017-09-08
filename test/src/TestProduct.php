<?php


namespace LazadaApi\Product;


include_once __DIR__ . '/../../vendor/autoload.php';


function testGetProducts()
{
    $builder = new ProductBuilder();

    $builder->fetchAttributeByPrimaryCategory(7940, 'vietpiano@gmail.com', 'jLSYvK0SNlNxYyT-eaIAalSEggUT4ezP78bs4MjZJvb-6mXJlQc4TGzD');
    $builder->setAttributesValues([
        'name' => 'Chiếc áo bà Năm',
        'short_description' => '<p>Áo này là áo bà ba</p>',
        'brand' => 'Viettien',
        'color_family' => 'Brown,Gold,Blue,Beige,Multicolor,Black,Green,Grey',
        'warranty_type' => 'No Warranty',
        'warranty' => '1 Month',

        'Status' => 'active',
        'quantity' => '10',
        '_compatible_variation_' => 'Int:XL',
        'SellerSku' => 'BS0123991356733',
        'package_content' => '<p>&Aacute;o</p>',
        'package_width' => '20',
        'package_height' => '20',
        'size' => 'Int:XL',
        'special_price' => '0.0',
        'price' => '500000.0',
        'package_length' => '20',
        'package_weight' => '0.1',
        'Available' => '10',
        'Images' => [
            'http://vn-live-02.slatic.net/p/7/ao-di-gat-5677-97327321-532b2d2876538c3715d898c49f8bd816-catalog.jpg'
        ]
    ]);

//    $product = $builder->build();

    $xml = $builder->buildXmlProductRequest();
    $request = new CreateProduct('vietpiano@gmail.com', 'jLSYvK0SNlNxYyT-eaIAalSEggUT4ezP78bs4MjZJvb-6mXJlQc4TGzD');
    $r = $request->execute($xml);

    var_dump($r);
}

testGetProducts();