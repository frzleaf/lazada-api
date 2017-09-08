<?php
/**
 * Created by PhpStorm.
 * User: balol
 * Date: 9/8/2017
 * Time: 3:17 PM
 */


if (!function_exists('xml_from_array')) {

    function xml_from_array($data, $root_name = null)
    {
        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }

        if ($root_name === null) {
            $root_name = array_keys($data)[0];
        }

        $xml = new SimpleXMLElement("<$root_name/>");

        array_to_xml($data, $xml);

        return $xml->asXML();
    }

}


if (!function_exists('array_to_xml')) {

    /**
     * @param $data
     * @param $xml_data SimpleXMLElement
     */
    function array_to_xml($data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $name = $xml_data->getName();
                if (preg_match('/.+s$/', $name)) {
                    $name = $xml_data->getName();
                    $key = substr($name, 0, strlen($name) - 1);

                } else {
                    $key = 'item' . $key; // Dealing with <0/>..<n/> issues
                }
            }
            if (is_array($value)) {
                $sub_node = $xml_data->addChild($key);
                array_to_xml($value, $sub_node);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

}


if (!function_exists('array_from')) {

    /**
     * @param $data
     *
     * @return array
     */
    function array_from($data)
    {
        return json_decode(json_encode($data), true);
    }

}


