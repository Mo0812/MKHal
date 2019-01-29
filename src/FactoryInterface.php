<?php
namespace MK\HAL;

interface FactoryInterface {

    /**
     * Interface for output different HAL formats
     *
     * @param HALObject $halObject
     * @return mixed
     */
    static public function serialize(HALObject $halObject);
}