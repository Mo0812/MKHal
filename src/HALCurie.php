<?php
namespace MK\HAL;

/**
 * Curie representation of HAL sepcification. This class uses and extends HALLink
 */
class HALCurie extends HALLink implements \JsonSerializable {

    function __construct($name, $href, $templated = true) {
        parent::__construct($href, null, $name, $templated);
    }
}