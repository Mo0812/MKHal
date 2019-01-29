<?php
namespace MK\HAL;

class HALObject implements \JsonSerializable {

    private $self;
    private $curies;
    private $links;
    private $data;
    private $embedded;

    function __construct($self, $data = null) {
        $this->self = $self;
        $this->data = $data;
        $this->curies = array();
        $this->links = array();
        $this->embedded = array();
    }

    public function addCurie(HALCurie $curie) {
        $this->curies[] = $curie;
    }

    public function getCuries() {
        return $this->curies;
    }

    public function addLink(string $label, HALLink $link) {
        $this->addAttributeChaining("links", $label, $link);
    }

    public function addLinkCollection(string $label, array $links) {
        foreach($links as $link) {
            $this->addAttributeChaining("links", $label, $link);
        }
    }

    public function getLink($label) {
        return $this->links[$label];
    }

    public function getLinks() {
        return $this->links;
    }

    public function embed(string $label, HALObject $object) {
        $this->addAttributeChaining("embedded", $label, $object);
    }

    public function embedCollection(string $label, array $objects) {
        foreach($objects as $object) {
            $this->addAttributeChaining("embedded", $label, $object);
        }
    }

    public function getEmbedded($label) {
        return $this->embedded[$label];
    }

    public function getAllEmbedded() {
        return $this->embedded;
    }

    public function exportJson() {
        return json_encode($this);
    }

    public function jsonSerialize() {
        $hal = array(
            "_links" => array(
                "self" => array(
                    "href" => $this->self
                )
            )
        );
        foreach($this->curies as $curie) {
            if(isset($hal["_links"]["curies"])) {
                $hal["_links"]["curies"][] = $curie;
            } else {
                $hal["_links"]["curies"] = array($curie);
            }
        }
        foreach($this->links as $label => $link) {
            $hal["_links"][$label] = $link;
        }
        foreach($this->embedded as $label => $objects) {
            $hal["_embedded"][$label] = $objects;
        }
        return $hal;
    }

    private function addAttributeChaining(string $attribute, string $label, $data) {
        if(isset($this->$attribute[$label])) {
            $prev_attrs = $this->$attribute[$label];
            $prev_attrs = is_array($prev_attrs) ? $prev_attrs : array($prev_attrs);
            $this->$attribute[$label] = array_merge($prev_attrs, array($data));
        } else {
            $this->$attribute[$label] = $data;
        }
    }
}