<?php
namespace MK\HAL;

/**
 * Factory class for generating JSON output
 */
class JSONFactory implements FactoryInterface{

    /**
     * Converts a HALObject object into an array structure which can be outputted as JSON representation according to the HAL specification.
     *
     * @param HALObject $halObject
     * @return array
     */
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
        $hal = array_merge($hal, $halObject->getData());
        return $hal;
    }
}