<?php
namespace MK\HAL;

interface FactoryInterface {

    static public function serialize(HALObject $halObject);
}