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

    public function getSelf() {
        return $this->self;
    }

    public function addCurie(HALCurie $curie) {
        $this->curies[] = $curie;
    }

    public function getCuries() {
        return $this->curies;
    }

    public function addLink(string $label, HALLink $link, $mode = HALMode::APPEND) {
        $this->addAttributeChaining("links", $label, $link, $mode);
    }

    public function addLinkCollection(string $label, array $links, $mode = HALMode::APPEND) {
        foreach($links as $link) {
            $this->addAttributeChaining("links", $label, $link, $mode);
        }
    }

    public function getLink($label) {
        return $this->links[$label];
    }

    public function getLinks() {
        return $this->links;
    }

    public function embed(string $label, HALObject $object, $mode = HALMode::APPEND) {
        $this->addAttributeChaining("embedded", $label, $object, $mode);
    }

    public function embedCollection(string $label, array $objects, $mode = HALMode::APPEND) {
        foreach($objects as $object) {
            $this->addAttributeChaining("embedded", $label, $object, $mode);
        }
    }

    public function getEmbedded($label) {
        return $this->embedded[$label];
    }

    public function getAllEmbedded() {
        return $this->embedded;
    }

    public function export() {
        return json_encode($this);
    }

    public function jsonSerialize() {
        return JSONFactory::serialize($this);
    }

    private function addAttributeChaining(string $attribute, string $label, $data, $mode) {
        if(isset($this->$attribute[$label]) && $mode !== HALMode::OVERWRITE) {
            $prev_attrs = $this->$attribute[$label];
            $prev_attrs = is_array($prev_attrs) ? $prev_attrs : array($prev_attrs);
            $this->$attribute[$label] = array_merge($prev_attrs, array($data));
        } else {
            $this->$attribute[$label] = $data;
        }
    }
}