<?php
namespace MK\HAL;

/**
 * Class representation of HAL resource type
 */
class HALObject implements \JsonSerializable {

    private $self;
    private $curies;
    private $links;
    private $data;
    private $embedded;

    /**
     * Representation of a HAL resource
     *
     * @param string $self
     * @param mixed $data
     */
    function __construct(string $self, $data = array()) {
        $this->self = $self;
        $this->data = $data;
        $this->curies = array();
        $this->links = array();
        $this->embedded = array();
    }

    /**
     * Return self link attribute
     *
     * @return string
     */
    public function getSelf() {
        return $this->self;
    }

    /**
     * Add an array with additional data beside the HAL attributes
     *
     * @param array $data
     * @return void
     */
    public function addData(array $data) {
        $this->data = $data;
    }

    /**
     * Returns the embedded data
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * 
     * Add a curie to the curies attribute
     *
     * @param HALCurie $curie
     * @return void
     */
    public function addCurie(HALCurie $curie) {
        $this->curies[] = $curie;
    }

    /**
     * Return all curies set in curies attribute
     *
     * @return HALCurie[]
     */
    public function getCuries() {
        return $this->curies;
    }

    /**
     * Add a link to the _links attribute (self not supported)
     *
     * @param string $label
     * @param HALLink $link
     * @param int $mode
     * @return void
     */
    public function addLink(string $label, HALLink $link, int $mode = HALMode::APPEND) {
        $this->addAttributeChaining("links", $label, $link, $mode);
    }

    /**
     * Add multiple links to according to one attribute name (label)
     *
     * @param string $label
     * @param array $links
     * @param int $mode
     * @return void
     */
    public function addLinkCollection(string $label, array $links, int $mode = HALMode::APPEND) {
        foreach($links as $link) {
            $this->addAttributeChaining("links", $label, $link, $mode);
        }
    }

    /**
     * Returns link(s) according to given attribute name (label)
     *
     * @param string $label
     * @return HALLink[]|HALLink
     */
    public function getLink(string $label) {
        return $this->links[$label];
    }

    /**
     * Returns all included links
     *
     * @return array
     */
    public function getLinks() {
        return $this->links;
    }

    /**
     * Embed another HAL resource to the _embedded attribute under one attribute name (label)
     *
     * @param string $label
     * @param HALObject $object
     * @param int $mode
     * @return void
     */
    public function embed(string $label, HALObject $object, int $mode = HALMode::APPEND) {
        $this->addAttributeChaining("embedded", $label, $object, $mode);
    }

    /**
     * Embed multiple HAL resources to the _embedded attribute under one attribute name (label)
     *
     * @param string $label
     * @param array $objects
     * @param int $mode
     * @return void
     */
    public function embedCollection(string $label, array $objects, int $mode = HALMode::APPEND) {
        foreach($objects as $object) {
            $this->addAttributeChaining("embedded", $label, $object, $mode);
        }
    }

    /**
     * Returns the embedded HAL resources according to the attribute name (label)
     *
     * @param string $label
     * @return HALObject[]|HALObject
     */
    public function getEmbedded(string $label) {
        return $this->embedded[$label];
    }

    /**
     * Returns all included HAL resources from the _embedded attribute
     *
     * @return array
     */
    public function getAllEmbedded() {
        return $this->embedded;
    }

    /**
     * Returns the JSON representation of the HALObject (short handle for json_encode)
     *
     * @return string
     */
    public function export() {
        return json_encode($this);
    }

    /**
     * Interface implementation, triggered by json_encode call
     *
     * @return array
     */
    public function jsonSerialize() {
        return JSONFactory::serialize($this);
    }

    /**
     * Adds given array or object to an attribute
     *
     * @param string $attribute
     * @param string $label
     * @param mixed $data
     * @param int $mode
     * @return void
     */
    private function addAttributeChaining(string $attribute, string $label, $data, int $mode) {
        if(isset($this->$attribute[$label]) && $mode !== HALMode::OVERWRITE) {
            $prev_attrs = $this->$attribute[$label];
            $prev_attrs = is_array($prev_attrs) ? $prev_attrs : array($prev_attrs);
            $this->$attribute[$label] = array_merge($prev_attrs, array($data));
        } else {
            $this->$attribute[$label] = $data;
        }
    }
}