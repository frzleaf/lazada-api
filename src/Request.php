<?php


namespace ApiLazada;

use \DateTime;
use SimpleXMLElement;

/**
 * Common class for API request
 *
 * @property string Action
 * @property string Version
 * @property string Timestamp
 * @property string UserID
 * @property string Signature
 */
class Request
{
    const API_URL = 'https://api.sellercenter.lazada.vn';
    const API_VERSION = '1.0';

    protected $UserId = null;
    protected $ApiToken;


    protected $MethodName = null;
    protected $RequestParams = [];
    protected $ErrorResponse = [];
    protected $Format = 'JSON';

    public function __construct($user, $api_token)
    {
        $this->UserId = $user;
        $this->ApiToken = $api_token;
        $reflect = new \ReflectionClass($this);
        $this->MethodName = $reflect->getShortName();
    }

    public function query($params = null)
    {
        if ($params) {
            $this->RequestParams = $params;
        }

        $request_params = $this->params();
        $request_params = $this->sign($request_params);
        $response = $this->curl($request_params);

        $data = $this->convert($response, strtolower($this->Format) == 'xml');

        return $this->resolveResponse($data);
    }

    /**
     * Extract data from response array
     * @param array $data
     * @return array|null
     * @throws Exception
     */
    protected function resolveResponse($data = [])
    {
        if (isset($data['SuccessResponse'])) {
            return $data['SuccessResponse']['Body'];
        } elseif (isset($data['ErrorResponse'])) {
            $head = $data['ErrorResponse']['Head'];
            throw new Exception(sprintf("Lazada error code: %s, %s", $head['ErrorCode'], $head['ErrorMessage']));
        }
        return null;
    }


    /**
     * Init common params
     * @return array
     */
    protected function params()
    {
        $default = [
            "Action" => $this->MethodName,
            "UserID" => $this->UserId,
            "Version" => static::API_VERSION,
            "Format" => $this->Format,
            "Timestamp" => (new DateTime())->format(DateTime::ISO8601),
        ];

        $result = array_replace($this->RequestParams, $default);

        return $result;
    }

    /**
     * Sign request parameters
     * @param $params array
     * @return array
     */
    protected function sign($params)
    {
        ksort($params);
        $strToSign = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $signature = rawurlencode(hash_hmac('sha256', $strToSign, $this->ApiToken, false));
        $params['Signature'] = $signature;
        return $params;
    }

    /**
     * Make request to API url
     * @param $params array
     * @param $headers
     * @param string $method
     * @param null $raw_body
     * @param $info array - reference for curl status info
     * @return string
     */
    protected function curl($params, $headers = [], $method = 'get', $raw_body = null, &$info = [])
    {
        $queryString = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        // Open Curl connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::API_URL . "?" . $queryString);

        if($headers){
            $joined_headers = [];
            foreach ($headers as $k => $v){
                $joined_headers = "$k: $v";
            }
            curl_setopt($ch, CURLOPT_HEADER, $joined_headers);
        }

        if (strtolower($method) == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);

            if ($raw_body) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $raw_body);
            }
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Convert response XML to associative array
     * @param $response string
     * @param bool $xml_format
     * @return array
     * @throws Exception
     */
    protected function convert($response, $xml_format = true)
    {
        if ($response == null) {
            return [];
        }

        if ($xml_format) {
            $obj = simplexml_load_string($response);
            $data = json_decode(json_encode($obj), true);
            $data = [
                $obj->getName() => $data
            ];

        } else {
            $data = json_decode($response, true);
            if (json_last_error()) {
                throw new Exception('JSON Response error: ' . json_last_error_msg());
            }
        }

        if (is_array($data)) {
            $data = $this->sanitize($data);
            return $data;
        }

        return null;
    }

    /**
     * Clear array after convert. Remove empty arrays and change to string
     * @param $arr array
     * @return array
     */
    protected function sanitize($arr)
    {
        foreach ($arr AS $k => $v) {
            if (is_array($v)) {
                if (count($v) > 0) {
                    $arr[$k] = $this->sanitize($v);
                } else {
                    $arr[$k] = "";
                }
            }
        }
        return $arr;
    }


    public function __set($name, $value)
    {
        if ($name) {
            $this->RequestParams[$name] = $value;
        }
    }


    public function __get($name)
    {
        if ($name && isset($this->RequestParams[$name])) {
            return $this->RequestParams[$name];
        }
        return null;
    }
}