<?php


namespace LazadaApi;


/**
 *
 * Class GetProducts
 * @package LazadaApi\Product
 */
class PostRequest extends Request
{


    public function execute($request_body = null)
    {
        $this->Format = 'XML';

        if ($request_body == null) {
            throw new Exception('Can not create product with empty product_params');
        }

        $request_params = $this->params();
        $request_params = $this->sign($request_params);
        $response = $this->curl($request_params, [], 'POST', $request_body);

        $data = $this->convert($response);

        return $this->resolveResponse($data);
    }

}