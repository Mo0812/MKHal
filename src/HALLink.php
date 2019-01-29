<?php
namespace MK\HAL;

class HALLink implements \JsonSerializable {

    public $label;
    public $href;

    function __construct($label, $href) {
        $this->label = $label;
        $this->href = $href;
    }

    public function jsonSerialize() {
        return array(
            $this->label => array(
                "href" => $this->href
            )
        );
    }

}