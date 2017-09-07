<?php


namespace ApiLazada;


/**
 *
 * Class GetProducts
 * @package ApiLazada\Product
 */
class PostRequest extends Request
{


    public function query($product_params = null)
    {

        if ($product_params == null) {
            throw new Exception('Can not create product with empty product_params');
        }

        $xml_string = $this->createXmlFromArray($product_params);

        $request_params = $this->params();
        $request_params = $this->sign($request_params);
        $response = $this->curl($request_params, [], 'POST', $xml_string);

        $data = $this->convert($response);

        return $this->resolveResponse($data);
    }


    protected function createXmlFromArray($data)
    {
        $xml = new \SimpleXMLElement('<request/>');
        array_walk_recursive($data, [$xml, 'addChild']);
        return $xml->asXML();
    }

}