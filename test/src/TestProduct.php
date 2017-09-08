<?php


namespace LazadaApi\Product;



include_once __DIR__ . '/../../vendor/autoload.php';


function testGetProducts()
{
    $request = new GetCategoryTree('vietpiano@gmail.com', 'jLSYvK0SNlNxYyT-eaIAalSEggUT4ezP78bs4MjZJvb-6mXJlQc4TGzD');
    $data = $request->query();
    var_dump($data);
}

testGetProducts();