<?php


namespace LazadaApi\Product;

use LazadaApi\Order\GetOrders;

include_once __DIR__ . '/../../vendor/autoload.php';


function testGetProducts()
{
    $request = new GetOrders('vietpiano@gmail.com', 'jLSYvK0SNlNxYyT-eaIAalSEggUT4ezP78bs4MjZJvb-6mXJlQc4TGzD');
    $request->Limit = 1;
    $request->Offset = 1;
    $data = $request->query();
    var_dump($data);
}

testGetProducts();