<?php
namespace MK\HAL;

class HALCurie implements \JsonSerializable {

    public $name;
    public $href;
    public $templated;

    function __construct($name, $href, $templated = true) {
        $this->name = $name;
        $this->href = $href;
        $this->templated = $templated;
    }

    public function jsonSerialize() {
        return array(
            "name" => $this->name,
            "href" => $this->href,
            "templated" => $this->templated
        );
    }
}