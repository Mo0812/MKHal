<?php
namespace MK\HAL;

class HALObject implements \JsonSerializable {

    public $self;
    private $data;
    private $links;
    private $embedded;

    function __construct($self, $data = null) {
        $this->self = $self;
        $this->data = $data;
        $this->links = array();
        $this->embedded = array();
    }

    public function addLink(HALLink $link) {
        $this->links[$link->label] = $link;
    }

    public function getLinks() {
        return $this->links;
    }

    public function embed(string $label, HALObject $object) {
        
        if(isset($this->embedded[$label])) {
            $this->embedded[$label][] = $object;
        } else {
            $this->embedded[$label] = array($object);
        }
    }

    public function embedCollection(string $label, array $objects) {
        if(!empty($this->embedded[$label])) {
            $this->embedded[$label] = array_merge($this->embedded[$label], $objects);
        } else {
            $this->embedded[$label] = $objects;
        }
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
        foreach($this->links as $link) {
            $hal["_links"][$link->label] = array(
                "href" => $link->href
            );
        }
        foreach($this->embedded as $label => $objects) {
            $hal["_embedded"][$label] = $objects;
        }
        return $hal;
    }
}