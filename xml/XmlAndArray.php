<?php
namespace MyCode\Xml;

/**
 * Class XmlAndArray
 * @package MyCode\Xml
 * @author zhuangzhiwei
 * @todu xml和array的相互转换
 */
class XmlAndArray{
    /**
     * 数组转xml
     * @param array $arry
     * @param int $mark
     * @return string
     */
    function arrayToXml($arry = array(), $mark = 0){
        if ($mark == 0){
            $xml = "<?xml version='1.0' encoding='UTF-8'?>";
        }else{
            $xml = "";
        }
        foreach ($arry as $key => $value){
            if (is_array($value)){
                $xml .= "<".$key.">".$this->arrayToXml($value, 1)."</".$key.">";
            }elseif (is_numeric($value)){
                $xml .= "<".$key.">".$value."</".$key.">";
            }else{
                $xml .= "<".$key."><![CDATA[".$value."]]></".$key.">";
            }
        }
        return $xml;
    }

    /**
     * @param array $arr
     * @param int $dom
     * @param int $item
     * @return string
     * 利用DOMDocument来生成xml结构
     */
    function arrayToXml2($arr = array(), $dom = 0, $item = 0){
        if (!$dom){
            $dom = new \DOMDocument("1.0");
        }
        if(!$item){
            $item = $dom->createElement("root");
            $dom->appendChild($item);
        }
        foreach ($arr as $key => $val){
            $itemx = $dom->createElement(is_string($key)?$key:"item");
            $item->appendChild($itemx);
            if (!is_array($val)){
                $text = $dom->createTextNode($val);
                $itemx->appendChild($text);
            }else{
                $this->arrayToXml2($val,$dom,$itemx);
            }
        }
        return $dom->saveXML();
    }

    /**
     * @param $xml
     * @return mixed
     * 
     */
    function xmlToArray($xml){
        //禁止外部引用实体
        libxml_disable_entity_loader(true);
        $xmlString = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlString),true);
        return $val;
    }
}