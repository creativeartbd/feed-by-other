<?php

namespace App\Http\Controllers;

use SimpleXMLElement;
use JsonSerializable;

class ParserGoogleFeed extends SimpleXMLElement implements JsonSerializable
{

    public function jsonSerialize()
    {
        $array = array();

        // json encode attributes if any.
        if ($attributes = $this->attributes()) {
            $array['@attributes'] = iterator_to_array($attributes);
        }

        $namespaces = [null] + $this->getDocNamespaces(true);
        // json encode child elements if any. group on duplicate names as an array.
        foreach ($namespaces as $namespace) {
            foreach ($this->children($namespace) as $name => $element) {
                if (isset($array[$name])) {
                    if (!is_array($array[$name])) {
                        $array[$name] = [$array[$name]];
                    }
                    $array[$name][] = $element;
                } else {
                    $array[$name] = $element;
                }
            }
        }

        // json encode non-whitespace element simplexml text values.
        $text = trim($this);
        if (strlen($text)) {
            if ($array) {
                $array['@text'] = $text;
            } else {
                $array = $text;
            }
        }

        // return empty elements as NULL (self-closing or empty tags)
        if (!$array) {
            $array = NULL;
        }

        return $array;
    }

    public function toArray() {
        return (array) json_decode(json_encode($this));
    }


    public static function get_data_from_xml($url) {

        $array_parents = array('item','product');

        $xml = new \XMLReader();
        $read = $xml->open($url);
        $unique_columns = array();

        while ($xml->read() &&  !in_array($xml->name, $array_parents))
        {
        }

        while ( in_array($xml->name, $array_parents) )
        {
            $xml_element = $xml->readOuterXML();
            $feed = new ParserGoogleFeed($xml_element, LIBXML_NOCDATA);
            $parse = $feed->toArray();

            if(!empty( $parse ))
            {
                foreach($parse as $k => $v){
                    if(!in_array($k,  $unique_columns)){
                        $unique_columns[] = $k;
                    }
                }
            }

            $xml->next($xml->name);
        }

        $xml->close();
        unset($xml);

        return $unique_columns;
    }
}





