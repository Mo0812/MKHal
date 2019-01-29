<?php
namespace MK\HAL;

class HALLink implements \JsonSerializable {

    private $href;

    function __construct($href) {
        $this->href = $href;
    }

    public function jsonSerialize() {
        return array(
            "href" => $this->href
        );
    }

}