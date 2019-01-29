<?php
namespace MK\HAL;

class JSONFactory implements FactoryInterface{

    static public function serialize(HALObject $halObject) {
        $hal = array(
            "_links" => array(
                "self" => array(
                    "href" => $halObject->getSelf()
                )
            )
        );
        foreach($halObject->getCuries() as $curie) {
            if(isset($hal["_links"]["curies"])) {
                $hal["_links"]["curies"][] = $curie;
            } else {
                $hal["_links"]["curies"] = array($curie);
            }
        }
        foreach($halObject->getLinks() as $label => $link) {
            $hal["_links"][$label] = $link;
        }
        foreach($halObject->getAllEmbedded() as $label => $objects) {
            $hal["_embedded"][$label] = $objects;
        }
        return $hal;
    }
}