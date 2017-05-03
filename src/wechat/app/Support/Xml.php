<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihaitao
 * Date: 17-5-3
 * Time: 下午2:02
 */

namespace wechat\app\Support;


class Xml
{

    public static function parse($xml)
    {
        return self::normalize(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS));
    }

    public static function build($data, $root = 'xml')
    {
        $xml = "<{$root}>";
        $xml  .= self::data2Xml($data);
        $xml  .= "</{$root}>";
        return $xml;
    }


    protected static function normalize($obj)
    {
        $result = null;

        if (is_object($obj)) {
            $obj = (array) $obj;
        }

        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $res = self::normalize($value);
                if (($key === '@attributes') && ($key)) {
                    $result = $res;
                } else {
                    $result[$key] = $res;
                }
            }
        } else {
            $result = $obj;
        }

        return $result;
    }


    protected static function data2Xml($data)
    {
        $xml = '';

        foreach ($data as $key => $val) {

            $xml .= "<{$key}>";

            if ((is_array($val) || is_object($val))) {
                $xml .= self::data2Xml((array) $val);
            } else {
                $xml .= is_numeric($val) ? $val : self::cdata($val);
            }

            $xml .= "</{$key}>";
        }

        return $xml;
    }


    public static function cdata($string)
    {
        return sprintf('<![CDATA[%s]]>', $string);
    }

}